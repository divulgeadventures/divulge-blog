#!/usr/bin/env python3
"""Check the RSS feeds that Pinterest auto-publish reads (read-only).

Saves site-data/feeds.json with, for every feed: HTTP status, whether the XML parses,
number of items, newest item date, and how many items carry an image
(<enclosure> or <media:content>), plus the first few items. Pinterest skips items
without an image and stops reading a feed that is broken or not RSS.
"""
import json
import time
import re
import urllib.error
import urllib.request
import xml.etree.ElementTree as ET
from datetime import datetime, timezone

UA = "Mozilla/5.0 (compatible; DivulgeFeedCheck/1.0; +https://github.com/divulgeadventures/divulge-blog)"
SITES = {
    "divulge": "https://divulgeadventures.com",
    "hogh": "https://hikingoutdoorhub.com",
}
MEDIA = "{http://search.yahoo.com/mrss/}content"


def get(url):
    req = urllib.request.Request(url, headers={"User-Agent": UA, "Accept": "*/*"})
    try:
        with urllib.request.urlopen(req, timeout=40) as r:
            return r.status, r.read().decode("utf-8", "replace"), r.headers.get("Content-Type", "")
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode("utf-8", "replace")[:2000], e.headers.get("Content-Type", "")
    except Exception as e:  # network error
        return 0, str(e), ""


def check(url):
    time.sleep(1.5)  # be gentle: fast bursts can trigger 503s from the host
    status, body, ctype = get(url)
    if status in (429, 503):
        time.sleep(15)
        status, body, ctype = get(url)
    res = {"url": url, "status": status, "content_type": ctype}
    start = body.lstrip()[:200]
    res["starts_with"] = start[:120]
    if body[:3] == "﻿" or (body and not body.lstrip().startswith("<?xml") and not body.lstrip().startswith("<rss")):
        res["warning"] = "Feed does not start with <?xml or <rss (extra output before the XML?)"
    try:
        root = ET.fromstring(body.lstrip().encode("utf-8"))
    except ET.ParseError as e:
        res["xml_ok"] = False
        res["xml_error"] = str(e)
        return res
    res["xml_ok"] = True
    items = root.findall("./channel/item")
    res["items"] = len(items)
    with_img = 0
    sample = []
    for it in items:
        enc = it.find("enclosure")
        med = it.find(MEDIA)
        img = (enc.get("url") if enc is not None else None) or (med.get("url") if med is not None else None)
        if img:
            with_img += 1
        if len(sample) < 5:
            sample.append({"title": (it.findtext("title") or "")[:90], "date": it.findtext("pubDate"), "image": img})
    res["items_with_image"] = with_img
    res["newest"] = items[0].findtext("pubDate") if items else None
    res["sample"] = sample
    return res


def terms(site, kind):
    slugs = []
    for page in range(1, 11):
        status, body, _ = get(f"{site}/wp-json/wp/v2/{kind}?per_page=100&page={page}&_fields=slug,count")
        if status != 200:
            break
        try:
            batch = json.loads(body)
        except Exception:
            break
        if not batch:
            break
        slugs += [t["slug"] for t in batch if t.get("count", 0) > 0]
        if len(batch) < 100:
            break
    return slugs


out = {"checked_at_utc": datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M"), "feeds": {}}
for name, site in SITES.items():
    urls = [f"{site}/feed/"]
    urls += [f"{site}/category/{s}/feed/" for s in terms(site, "categories")]
    tags = terms(site, "tags")
    countries = ["kenya", "tanzania", "uganda", "rwanda", "namibia", "botswana"]
    out.setdefault("tags", {})[name] = tags
    urls += [f"{site}/tag/{s}/feed/" for s in tags if s.split("-")[0] in countries or (name == "hogh" and s in ("hiking", "kenya"))]
    out["feeds"][name] = [check(u) for u in urls]

with open("site-data/feeds.json", "w") as f:
    json.dump(out, f, indent=1, ensure_ascii=False)
bad = [f["url"] for fs in out["feeds"].values() for f in fs if not f.get("xml_ok") or f.get("items_with_image", 0) < f.get("items", 0)]
print("feeds checked:", sum(len(v) for v in out["feeds"].values()), "| problems:", len(bad))
for b in bad:
    print(" -", b)

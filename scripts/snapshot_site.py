#!/usr/bin/env python3
"""Save a read-only copy of divulgeadventures.com data into site-data/.

Runs on GitHub Actions (see .github/workflows/site-snapshot.yml), so the scheduled
Claude runs can read the site from the repo without asking permission to open it.
Only public pages and the public WordPress REST API are read; nothing on the site changes.

Output:
  site-data/media.json        every image in the Media Library: id, url, alt, title, date
  site-data/trips.json        every published trip: id, slug, url, title, featured image, modified
  site-data/trips/<slug>.txt  the text of each trip page plus its images (src + alt)
  site-data/posts.json        every published blog post: id, slug, url, title, date
  site-data/pages.json        every published page: id, slug, url, title
  site-data/snapshot.json     when the copy was made and what failed
"""
import html
import json
import os
import re
import sys
import time
import urllib.error
import urllib.request
from datetime import datetime, timezone
from html.parser import HTMLParser

SITE = "https://divulgeadventures.com"
OUT = "site-data"
UA = "Mozilla/5.0 (compatible; DivulgeSnapshot/1.0; +https://github.com/divulgeadventures/divulge-blog)"
errors = []


def get(url, tries=3):
    for i in range(tries):
        try:
            req = urllib.request.Request(url, headers={"User-Agent": UA, "Accept": "*/*"})
            with urllib.request.urlopen(req, timeout=40) as r:
                return r.read().decode("utf-8", "replace"), dict(r.headers)
        except urllib.error.HTTPError as e:
            if e.code in (400, 404):
                return None, {}
            last = f"HTTP {e.code}"
        except Exception as e:  # network error, timeout
            last = str(e)
        time.sleep(3 * (i + 1))
    errors.append(f"{url}: {last}")
    return None, {}


def rest_all(endpoint, fields):
    items, page = [], 1
    while True:
        body, headers = get(f"{SITE}/wp-json/wp/v2/{endpoint}?per_page=100&page={page}&_fields={fields}")
        if not body:
            break
        try:
            batch = json.loads(body)
        except json.JSONDecodeError:
            errors.append(f"{endpoint} page {page}: not JSON (blocked by a firewall?)")
            break
        if not isinstance(batch, list) or not batch:
            break
        items += batch
        total = int(headers.get("X-WP-TotalPages") or headers.get("x-wp-totalpages") or 1)
        if page >= total:
            break
        page += 1
        time.sleep(1)
    return items


def text(v):
    if isinstance(v, dict):
        v = v.get("rendered", "")
    return html.unescape(re.sub(r"<[^>]+>", "", v or "")).strip()


class PageText(HTMLParser):
    """Readable text of a page (no scripts, styles, header, footer or nav) plus its images."""
    SKIP = {"script", "style", "noscript", "svg", "header", "footer", "nav", "form", "iframe"}
    BLOCK = {"p", "div", "li", "h1", "h2", "h3", "h4", "h5", "h6", "tr", "br", "section", "article", "td", "th", "dt", "dd"}

    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.skip = 0
        self.out = []
        self.imgs = []

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag in self.SKIP:
            self.skip += 1
        if self.skip:
            return
        if tag in ("h1", "h2", "h3", "h4"):
            self.out.append("\n\n" + "#" * int(tag[1]) + " ")
        elif tag in self.BLOCK:
            self.out.append("\n")
        if tag == "li":
            self.out.append("- ")
        if tag == "img":
            src = a.get("data-src") or a.get("data-lazy-src") or a.get("src") or ""
            if src and not src.startswith("data:"):
                self.imgs.append({"src": src, "alt": a.get("alt", "")})

    def handle_endtag(self, tag):
        if tag in self.SKIP and self.skip:
            self.skip -= 1

    def handle_data(self, data):
        if not self.skip and data.strip():
            self.out.append(data.strip() + " ")

    def result(self):
        t = "".join(self.out)
        t = re.sub(r"[ \t]+", " ", t)
        t = re.sub(r"\n\s*\n\s*\n+", "\n\n", t)
        return t.strip()


def save(name, data):
    with open(os.path.join(OUT, name), "w", encoding="utf-8") as f:
        json.dump(data, f, indent=1, ensure_ascii=False)
        f.write("\n")


def main():
    os.makedirs(os.path.join(OUT, "trips"), exist_ok=True)

    media = rest_all("media", "id,source_url,alt_text,title,date,media_type")
    media = [{"id": m["id"], "url": m.get("source_url", ""), "alt": m.get("alt_text", ""),
              "title": text(m.get("title")), "date": (m.get("date") or "")[:10]}
             for m in media if m.get("media_type", "image") == "image"]
    if media:
        save("media.json", media)

    posts = rest_all("posts", "id,slug,link,title,date")
    if posts:
        save("posts.json", [{"id": p["id"], "slug": p["slug"], "url": p["link"], "title": text(p.get("title")),
                             "date": (p.get("date") or "")[:10]} for p in posts])

    pages = rest_all("pages", "id,slug,link,title")
    if pages:
        save("pages.json", [{"id": p["id"], "slug": p["slug"], "url": p["link"], "title": text(p.get("title"))} for p in pages])

    trips = rest_all("trip", "id,slug,link,title,featured_media,destination,modified")
    by_id = {m["id"]: m for m in media}
    out_trips = []
    for t in trips:
        fm = by_id.get(t.get("featured_media"))
        out_trips.append({"id": t["id"], "slug": t["slug"], "url": t["link"], "title": text(t.get("title")),
                          "destination": t.get("destination", []), "modified": t.get("modified", ""),
                          "featured_image": {"id": fm["id"], "url": fm["url"], "alt": fm["alt"]} if fm else None})
        body, _ = get(t["link"])
        if body:
            p = PageText()
            p.feed(body)
            seen, imgs = set(), []
            for i in p.imgs:
                if i["src"] not in seen and "LOGO" not in i["src"] and "tripadvisor" not in i["src"].lower():
                    seen.add(i["src"])
                    imgs.append(i)
            with open(os.path.join(OUT, "trips", t["slug"] + ".txt"), "w", encoding="utf-8") as f:
                f.write(f"URL: {t['link']}\nID: {t['id']}\nTitle: {text(t.get('title'))}\nModified: {t.get('modified', '')}\n\n")
                f.write(p.result())
                f.write("\n\n## Images on the page (src | alt). Gallery photos come first; small 374x226 / 410x250 files are related-trip cards.\n")
                for i in imgs:
                    f.write(f"{i['src']} | {i['alt']}\n")
        time.sleep(1)
    if out_trips:
        save("trips.json", out_trips)

    save("snapshot.json", {
        "made_at_utc": datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M"),
        "counts": {"media": len(media), "posts": len(posts), "pages": len(pages), "trips": len(trips)},
        "errors": errors,
    })
    print(json.dumps({"media": len(media), "posts": len(posts), "pages": len(pages), "trips": len(trips), "errors": errors}, indent=1))
    if not media and not trips:
        sys.exit("Nothing could be read from the site (blocked?)")


if __name__ == "__main__":
    main()

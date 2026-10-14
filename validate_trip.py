#!/usr/bin/env python3
"""Check one or more trip package files before pushing.
Usage: python3 validate_trip.py trips/kenya-8080-....json [more files]
Exit code 1 if any check FAILS. WARN lines are advice only."""
import glob, json, re, sys
from html.parser import HTMLParser

REQUIRED = ["id", "slug", "focus_keyword", "rank_math_title", "rank_math_desc", "post_content",
            "overview", "highlights", "faq", "featured_image", "gallery", "status"]
BROAD = {"kenya safari", "tanzania safari", "uganda safari", "rwanda safari", "botswana safari",
         "namibia safari", "masai mara safari", "safari", "kenya safari packages", "african safari"}
SITE = "https://divulgeadventures.com"


class Links(HTMLParser):
    def __init__(self):
        super().__init__()
        self.links = []
        self.paras = []
        self._p = None

    def handle_starttag(self, tag, attrs):
        if tag == "a":
            self.links.append(dict(attrs).get("href", ""))
        if tag == "p":
            self._p = ""

    def handle_data(self, data):
        if self._p is not None:
            self._p += data

    def handle_endtag(self, tag):
        if tag == "p" and self._p is not None:
            self.paras.append(self._p.strip())
            self._p = None


def other_keywords(path):
    kws = {}
    for f in glob.glob("trips/*.json"):
        if f == path:
            continue
        try:
            for t in json.load(open(f)):
                if t.get("focus_keyword"):
                    kws[t["focus_keyword"].lower().strip()] = f
        except Exception:
            pass
    for f in glob.glob("posts/*.json"):
        try:
            k = json.load(open(f)).get("focus_keyword", "")
            if k:
                kws.setdefault(k.lower().strip(), f)
        except Exception:
            pass
    return kws


def check(path):
    fails, warns = [], []
    try:
        data = json.load(open(path))
    except Exception as e:
        return [f"not valid JSON: {e}"], []
    if not isinstance(data, list) or len(data) != 1:
        return ["file must be a JSON list with exactly one trip object"], []
    t = data[0]
    for k in REQUIRED:
        if k not in t or t[k] in ("", None, []):
            fails.append(f"missing field: {k}")
    if fails:
        return fails, warns
    if not isinstance(t["id"], int):
        fails.append("id must be the numeric WordPress trip ID")
    kw = t["focus_keyword"].strip()
    kwl = kw.lower()
    if kwl in BROAD or len(kwl.split()) < 4:
        fails.append(f'focus keyword "{kw}" is too broad (use 4+ words specific to this trip)')
    others = other_keywords(path)
    if kwl in others:
        fails.append(f'focus keyword "{kw}" is already used by {others[kwl]}')
    title, desc = t["rank_math_title"], t["rank_math_desc"]
    if len(title) > 60:
        fails.append(f"SEO title is {len(title)} characters (max 60)")
    if not title.lower().startswith(kwl):
        warns.append("SEO title does not start with the focus keyword")
    if len(desc) > 160:
        fails.append(f"meta description is {len(desc)} characters (max 160)")
    if kwl not in desc.lower():
        fails.append("meta description does not contain the focus keyword")
    p = Links()
    p.feed(t["overview"])
    if not p.paras or kwl not in p.paras[0].lower():
        fails.append("first overview paragraph must contain the focus keyword")
    first_words = len(p.paras[0].split()) if p.paras else 0
    if first_words > 75:
        warns.append(f"first overview paragraph is {first_words} words (aim for 40-70)")
    for para in p.paras:
        if len(para.split()) > 110:
            fails.append(f"paragraph over 110 words: {para[:60]}...")
    h2 = re.findall(r"<h2>(.*?)</h2>", t["overview"], re.S)
    if len(h2) < 3:
        fails.append("overview needs at least 3 H2 headings")
    if not any(kwl in h.lower() for h in h2):
        fails.append("no H2 contains the focus keyword")
    blog = [l for l in p.links if l.startswith(SITE) and "/trip/" not in l and "safari-proposal-request" not in l and l.rstrip("/") != SITE]
    if len(set(blog)) < 2:
        fails.append(f"needs 2-3 links to Divulge Diaries guides (found {len(set(blog))})")
    if not any("safari-proposal-request" in l for l in p.links):
        fails.append("missing link to the quote form (/safari-proposal-request/)")
    if not any(l.startswith("https://wa.me/254706223888") for l in p.links):
        fails.append("missing WhatsApp link in the price box")
    if re.search(r"(?<![\"'=])https?://[^\s<\"]+(?![^<]*</a>)", re.sub(r"<a [^>]*>.*?</a>", "", t["overview"], flags=re.S)):
        fails.append("raw URL in the overview text (wrap it in a link)")
    if not re.search(r"Price on request|From USD [\d,]+", t["overview"]):
        fails.append('price box must say "From USD x" (only as shown on the page) or "Price on request"')
    faq = t["faq"]
    if not 4 <= len(faq) <= 5:
        fails.append(f"needs 4-5 FAQs (found {len(faq)})")
    for qa in faq:
        if not qa.get("q") or not qa.get("a"):
            fails.append("an FAQ is missing q or a")
            continue
        n = len(qa["a"].split())
        if n < 25 or n > 90:
            warns.append(f'FAQ answer is {n} words (aim 40-80): {qa["q"]}')
    if not any(kwl in qa.get("q", "").lower() for qa in faq):
        warns.append("no FAQ question contains the focus keyword")
    if not 3 <= len(t["highlights"]) <= 7:
        warns.append(f"{len(t['highlights'])} highlights (aim for 4-6)")
    imgs = [t["featured_image"]] + list(t["gallery"])
    urls = [i.get("url", "") for i in imgs]
    for i in imgs:
        if not i.get("url", "").startswith("https://"):
            fails.append(f"image url must be https: {i.get('url')}")
        alt = (i.get("alt") or "").strip()
        if len(alt) < 10:
            fails.append(f"missing or too short alt text: {i.get('url')}")
        if re.search(r"b2b|agent rates|safar\b|\.jpe?g|img_|dsc_", alt, re.I):
            fails.append(f"alt text looks wrong: {alt}")
    if len(set(urls)) != len(urls):
        fails.append("the same image is used twice")
    if kwl not in t["featured_image"].get("alt", "").lower():
        warns.append("featured image alt does not contain the focus keyword")
    if len(t["gallery"]) < 4:
        warns.append(f"gallery has {len(t['gallery'])} photos (aim for 4-6). The gallery REPLACES the trip's current gallery.")
    if any("unsplash.com" in u for u in urls) and "unsplash.com/?utm_source" not in t["overview"]:
        fails.append("Unsplash photos used but no photographer credit line at the end of the overview")
    if t["status"] not in ("publish", "draft"):
        fails.append('status must be "publish" or "draft"')
    return fails, warns


if __name__ == "__main__":
    bad = 0
    for path in sys.argv[1:]:
        fails, warns = check(path)
        print(("FAIL " if fails else "PASS ") + path)
        for f in fails:
            print("  FAIL:", f)
        for w in warns:
            print("  WARN:", w)
        bad += bool(fails)
    sys.exit(1 if bad else 0)

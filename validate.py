#!/usr/bin/env python3
"""Validate Divulge Diaries post JSON files against TEMPLATE.md rules.

Usage: python3 validate.py posts/2026-10-08-1-some-slug.json [more files]
Exit code 0 = all pass. Prints every failure.
"""
import json
import re
import sys
from html.parser import HTMLParser

CATEGORIES = {
    "Destination Tips", "Safari Themes", "Great Migration & River Crossing",
    "Accommodation Guide", "Safari Planning Tips", "Team Building",
}
SITE = "divulgeadventures.com"
MONEY_PATHS = ("/destinations/", "/activities/", "/trip-types/", "/trip/",
               "/mountain-climbing-safari-packages/", "/safari-deals-divulge-adventures/")
NOT_BLOG = MONEY_PATHS + ("/safari-proposal-request/", "/category/", "/divulge-diaries/",
                          "/travel-insurance", "/flying-doctors", "/yellow-fever", "/wp-content/")
BANNED_DOMAINS = ("safaribookings", "tripadvisor", "viator", "getyourguide", "hikingoutdoorhub")


class Parser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.stack, self.paras, self.headings, self.links, self.imgs = [], [], [], [], []
        self.tables = self.ols = 0
        self.cur = None
        self.bold_in_p = False
        self.text = []
        self.sequence = []  # (tag, text) for h2/h3/p order

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag in ("p", "h2", "h3", "li", "td", "th"):
            self.cur = [tag, ""]
        if tag in ("strong", "b") and self.cur and self.cur[0] == "p" and not self._in_box():
            self.bold_in_p = True
        if tag == "a":
            self.links.append(a)
        if tag == "img":
            self.imgs.append(a)
        if tag == "table":
            self.tables += 1
        if tag == "ol":
            self.ols += 1
        if tag == "div":
            self.stack.append(a.get("style", ""))

    def _in_box(self):
        return any("text-align:center" in s for s in self.stack)

    def handle_endtag(self, tag):
        if tag == "div" and self.stack:
            self.stack.pop()
        if self.cur and tag == self.cur[0]:
            t = re.sub(r"\s+", " ", self.cur[1]).strip()
            if tag == "p":
                self.paras.append((t, self._in_box()))
            if tag in ("h2", "h3"):
                self.headings.append((tag, t))
            self.sequence.append((tag, t, self._in_box()))
            self.cur = None

    def handle_data(self, data):
        self.text.append(data)
        if self.cur:
            self.cur[1] += data


def words(s):
    return re.findall(r"[A-Za-z0-9']+", s)


def check(path):
    errs = []
    try:
        p = json.load(open(path, encoding="utf-8"))
    except Exception as e:  # noqa
        return [f"invalid JSON: {e}"]
    refresh = bool(p.get("update_slug"))
    required = ["title", "slug", "category", "tags", "focus_keyword", "meta_title",
                "meta_description", "excerpt", "content", "faq", "featured_image_alt"]
    if not refresh:
        required += ["featured_image", "status", "publish_at"]
    for k in required:
        if not p.get(k):
            errs.append(f"missing field: {k}")
    if refresh and p.get("slug") != p.get("update_slug"):
        errs.append("refresh: slug must equal update_slug")
    if errs:
        return errs

    kw = p["focus_keyword"].lower().strip()
    if p["category"] not in CATEGORIES:
        errs.append(f"bad category: {p['category']}")
    if not 3 <= len(p["tags"]) <= 5:
        errs.append("tags must be 3-5")
    if len(p["meta_title"]) >= 60:
        errs.append(f"meta_title {len(p['meta_title'])} chars (must be < 60)")
    if not p["meta_title"].lower().startswith(kw):
        errs.append("meta_title must start with the focus keyword")
    if not re.search(r"\d", p["meta_title"]):
        errs.append("meta_title needs a number")
    if len(p["meta_description"]) >= 160:
        errs.append(f"meta_description {len(p['meta_description'])} chars (must be < 160)")
    if kw not in p["meta_description"].lower():
        errs.append("meta_description must contain the keyword")
    if re.search(r"20\d\d", p["title"] + p["slug"]):
        errs.append("no year in title or slug")
    if kw not in p["featured_image_alt"].lower():
        errs.append("featured_image_alt must contain the keyword")

    html = p["content"]
    if "<h1" in html.lower():
        errs.append("content must not contain an H1")
    ps = Parser()
    ps.feed(html)
    body_text = " ".join(ps.text)
    wc = len(words(body_text))
    if wc < 1600:
        errs.append(f"only {wc} words (min 1,600)")
    kw_count = len(re.findall(r"\b" + re.escape(kw) + r"\b", body_text.lower()))
    density = kw_count * len(words(kw)) / max(wc, 1) * 100
    raw_density = kw_count / max(wc, 1) * 100
    if not 0.9 <= raw_density <= 1.3:
        errs.append(f"keyword density {raw_density:.2f}% ({kw_count} uses in {wc} words); target 1-1.2%")

    h2 = [t for tag, t in ps.headings if tag == "h2"]
    h3 = [t for tag, t in ps.headings if tag == "h3"]
    if not any(kw in t.lower() for t in h2):
        errs.append("keyword not in any H2")
    if not any(kw in t.lower() for t in h3):
        errs.append("keyword not in any H3")
    if not any(t.lower().startswith("frequently asked") for t in h2):
        errs.append("missing 'Frequently Asked Questions' H2")
    if not any(t.lower().startswith("final thoughts") for t in h2):
        errs.append("missing 'Final Thoughts' H2")

    # FAQ section H3s
    seq = ps.sequence
    faq_h3, faq_answers, in_faq, last_q = [], [], False, None
    for tag, t, box in seq:
        if tag == "h2":
            in_faq = t.lower().startswith("frequently asked")
            continue
        if in_faq and tag == "h3":
            faq_h3.append(t)
            last_q = t
        elif in_faq and tag == "p" and last_q:
            faq_answers.append((last_q, t))
            last_q = None
    if not 4 <= len(faq_h3) <= 5:
        errs.append(f"FAQ has {len(faq_h3)} questions (need 4-5)")
    for q, a in faq_answers:
        n = len(words(a))
        if not 40 <= n <= 80:
            errs.append(f"FAQ answer {n} words (40-80): {q[:50]}")
    if len(p["faq"]) != len(faq_h3):
        errs.append("JSON faq count must match FAQ H3s in the content")
    for item in p["faq"]:
        if not item.get("q") or not item.get("a") or "<" in item.get("a", ""):
            errs.append("each faq item needs plain-text q and a")

    q_heads = [t for t in h2 + h3 if t.endswith("?") and t not in faq_h3]
    if len(q_heads) < 3:
        errs.append(f"only {len(q_heads)} question headings outside the FAQ (need 3)")

    # Paragraph checks
    body_ps = [t for t, box in ps.paras if not box and t]
    if not body_ps:
        errs.append("no paragraphs")
    else:
        first = body_ps[0]
        n = len(words(first))
        if not 40 <= n <= 60:
            errs.append(f"first paragraph {n} words (40-60)")
        first_sentence = re.split(r"(?<=[.!?])\s", first)[0].lower()
        if kw not in first_sentence:
            errs.append("keyword not in the first sentence")
    for t in body_ps:
        if len(words(t)) >= 120:
            errs.append(f"paragraph over 120 words: {t[:60]}")
    after_h2 = None
    for tag, t, box in seq:
        if tag == "h2":
            after_h2 = t
        elif tag == "p" and after_h2 and not box:
            if len(words(t)) >= 60:
                errs.append(f"first paragraph after H2 '{after_h2[:40]}' is {len(words(t))} words (< 60)")
            after_h2 = None
    if ps.bold_in_p:
        errs.append("bold text inside a paragraph")
    if ps.tables < 1:
        errs.append("needs at least one <table>")
    # Readable table headers: light brand background + dark green text on every <th>
    for th in re.findall(r"<th\b[^>]*>", html):
        s = th.lower().replace(" ", "")
        if "background-color:#f6efe7" not in s or "color:#043d0e" not in s:
            errs.append("table header cell must use the TEMPLATE.md style (background #f6efe7, text #043D0E)")
            break
    if re.search(r"<(thead|tr)[^>]*background-color:#(043d0e|0b789d)", html, re.I):
        errs.append("dark background on a table header row (hard to read); use the TEMPLATE.md table style")

    # Boxes in order
    order = [("Quick Facts box", "border-left:5px solid #C97A3D"),
             ("Trip CTA", "border:2px solid #043D0E"),
             ("Insider Tips box", "border-left:5px solid #C97A3D"),
             ("Plan My Safari CTA", "safari-proposal-request"),
             ("WhatsApp CTA", "wa.me/254706223888")]
    pos = 0
    for name, marker in order:
        i = html.find(marker, pos)
        if i < 0:
            errs.append(f"missing or out of order: {name}")
        else:
            pos = i + 1
    if "[POST_TOPIC]" in html or re.search(r"\[[A-Z][^\]]{2,40}\]", html):
        errs.append("unreplaced [placeholder] text in content")

    # Quick facts bullets
    m = re.search(r"Quick Facts</h3>\s*<ul[^>]*>(.*?)</ul>", html, re.S)
    if m:
        n = m.group(1).count("<li")
        if not 5 <= n <= 7:
            errs.append(f"Quick Facts has {n} bullets (5-7)")
    else:
        errs.append("Quick Facts box heading not found")

    # Links
    hrefs = [a.get("href", "") for a in ps.links]
    internal = [h for h in hrefs if SITE in h]
    money = [h for h in internal if any(x in h for x in MONEY_PATHS)]
    blog = [h for h in internal if not any(x in h for x in NOT_BLOG) and h.rstrip("/").split("/")[-1] != p["slug"]
            and h.rstrip("/") not in ("https://divulgeadventures.com", "https://www.divulgeadventures.com")]
    external = [a for a in ps.links if a.get("href", "").startswith("http") and SITE not in a.get("href", "")
                and "wa.me" not in a.get("href", "")]
    if len(money) < 1:
        errs.append("needs a link to a money page (destination/activity/trip/climbing)")
    if len(set(blog)) < 2:
        errs.append(f"needs 2+ links to existing Divulge Diaries posts (found {len(set(blog))})")
    if any(h.rstrip("/").split("/")[-1] == p["slug"] for h in internal):
        errs.append("links to itself")
    good_ext = [a for a in external if "noopener" in a.get("rel", "") and "nofollow" not in a.get("rel", "")]
    if not good_ext:
        errs.append("needs 1 dofollow external link with rel=\"noopener\"")
    for h in hrefs:
        if any(b in h.lower() for b in BANNED_DOMAINS):
            errs.append(f"banned link: {h}")

    # Images
    if not any(kw in (i.get("alt") or "").lower() for i in ps.imgs):
        errs.append("needs an <img> in the body with the keyword in its alt text")

    # Links must be real HTML links, never markdown like [text](url) or bare [https://...] text
    plain = re.sub(r"<[^>]+>", " ", html)
    if re.search(r"\]\(\s*https?://", plain) or re.search(r"\[\s*https?://", plain):
        errs.append("markdown-style link text found (e.g. [https://...](https://...)); use <a href> links")
    if re.search(r"(?<![\"'=>])https?://[^\s<]+", plain.replace("&nbsp;", " ")):
        errs.append("bare URL shown as text; wrap it in an <a href> link with descriptive anchor text")
    # Content rules
    low = body_text.lower()
    if "hiking outdoor" in low or "gear hub" in low:
        errs.append("mentions another business")
    return errs


def main(paths):
    bad = 0
    for path in paths:
        errs = check(path)
        if errs:
            bad += 1
            print(f"FAIL {path}")
            for e in errs:
                print("  -", e)
        else:
            print(f"PASS {path}")
    sys.exit(1 if bad else 0)


if __name__ == "__main__":
    main(sys.argv[1:])

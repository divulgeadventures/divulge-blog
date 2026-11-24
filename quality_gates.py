"""Quality gates for Divulge Diaries posts (added 24 Nov 2026), called from validate.py.

These exist so the site can publish more without Google treating it as mass-produced
content. A post FAILS validation when:

1. Overlap: its focus keyword (or title) is too close to an existing post's, i.e. it
   would compete with a page we already have. Refreshes of the same slug are exempt.
2. Sources: it has fewer than 2 links to official or authoritative sources
   (governments, park authorities, health bodies, tourism boards, conservation bodies).
3. Local detail: it has no link to a Divulge trip page, or it has neither an owner
   photo (divulgeadventures.com/wp-content/...) nor a field note from FIELD_NOTES.md
   (listed in the JSON field "field_notes_used").
4. Review: it has no "review" block from the critic pass in REVIEW.md, or the review
   score is below 8 while "status" is "publish". Low-scoring new posts must be saved
   with "status": "draft" so the owner can check them.
"""
import glob
import json
import os
import re

HERE = os.path.dirname(os.path.abspath(__file__))
PASS_SCORE = 8

OFFICIAL = re.compile(
    r"(\.gov(\.[a-z]{2})?/|\.gov$|\.go\.(ke|tz|ug)\b|\.gov\.(rw|bw|na|uk|za)\b|\.gouv\.|"
    r"kws\.go|tanzaniaparks|ncaa\.go|visitrwanda|rdb\.rw|ugandawildlife|magicalkenya|ecitizen|"
    r"etakenya|immigration\.|who\.int|cdc\.gov|fitfortravel|travelhealthpro|nathnac|nwr\.com\.na|"
    r"botswanatourism|namibiatourism|tanzaniatourism|tanzaniatourism|exploreuganda|visituganda|"
    r"narok\.go|maasaimara|maraconservancies|unesco\.org|iucn|birdlife|ebird\.org|wwf\.|"
    r"kenyabirds|naturekenya|olpejetaconservancy|nrt-kenya|smartraveller|state\.gov|gov\.uk|"
    r"kenya-airways\.com|kq\.com|kaa\.go\.ke|tcaa\.go\.tz|met\.go)",
    re.I,
)
STOP = set("""a an and the of in on for to with from by at or is are vs versus your our best top
guide how what when where which who why do does can should my me i it its this that these those
tour tours trip trips safari safaris travel holiday holidays""".split())
# Product words are stopwords so "masai mara cost" and "masai mara safari cost" match.
# Place names are kept, so the same topic for another country or park is not an overlap.


def _toks(s):
    s = re.sub(r"[^a-z0-9 ]", " ", (s or "").lower())
    out = []
    for w in s.split():
        if w in STOP or len(w) < 2:
            continue
        if len(w) > 4 and w.endswith("s") and not w.endswith("ss"):
            w = w[:-1]
        out.append(w)
    return set(out)


def _jacc(a, b):
    return len(a & b) / len(a | b) if a and b else 0.0


def _existing(own_slug, own_path):
    items = []
    try:
        for x in json.load(open(os.path.join(HERE, "site-data", "posts.json"), encoding="utf-8")):
            items.append((x.get("slug", ""), x.get("title", ""), ""))
    except Exception:  # noqa
        pass
    for f in glob.glob(os.path.join(HERE, "posts", "*.json")):
        if os.path.abspath(f) == os.path.abspath(own_path) or os.path.basename(f).startswith("refresh-"):
            continue
        try:
            q = json.load(open(f, encoding="utf-8"))
        except Exception:  # noqa
            continue
        items.append((q.get("slug", ""), q.get("title", ""), q.get("focus_keyword", "")))
    return [i for i in items if i[0] and i[0] != own_slug]


def overlap(p, path):
    slug = p.get("update_slug") or p.get("slug")
    kw, title = _toks(p.get("focus_keyword")), _toks(p.get("title"))
    errs = []
    for s, t, k in _existing(slug, path):
        kt, tt = _toks(k), _toks(t)
        if (kt and kw and kt == kw) or (len(kw) >= 2 and kw <= tt and _jacc(kw, tt) >= 0.6) or _jacc(title, tt) >= 0.75:
            errs.append(f'overlaps an existing post: /{s}/ ("{t}"). Pick a different angle or skip this topic')
            if len(errs) >= 2:
                break
    return errs


def sources(html):
    links = set(re.findall(r'href="(https?://[^"]+)"', html))
    off = {re.sub(r"^https?://(www\.)?", "", l).split("/")[0] for l in links if OFFICIAL.search(l)}
    if len(off) < 2:
        return [f"needs links to at least 2 different official or authoritative sources (found {len(off)}): "
                "park authority, government, health body, tourism board or conservation body"]
    return []


def local_detail(p, html):
    errs = []
    if not re.search(r'href="https://divulgeadventures\.com/trip/', html):
        errs.append("needs at least 1 link to a Divulge trip page (https://divulgeadventures.com/trip/...)")
    owner_img = re.search(r'src="https://divulgeadventures\.com/wp-content/', html) or \
        "divulgeadventures.com/wp-content/" in (p.get("featured_image") or "")
    if not owner_img and not p.get("field_notes_used"):
        errs.append('needs local detail: an owner photo from site-data/media.json or a note from FIELD_NOTES.md '
                    '(list it in "field_notes_used")')
    return errs


def review(p):
    r = p.get("review")
    if not isinstance(r, dict) or "score" not in r:
        return ['missing "review" block from the critic pass (see REVIEW.md): {"score": n, "vs_top3": "...", "fixes": "..."}']
    try:
        score = float(r["score"])
    except Exception:  # noqa
        return ['"review.score" must be a number from 1 to 10']
    if score < PASS_SCORE and p.get("status") == "publish":
        return [f'review score {score:g} is below {PASS_SCORE}: improve the post and re-review, or save it with "status": "draft" for the owner']
    if score < PASS_SCORE and p.get("update_slug"):
        return [f"refresh review score {score:g} is below {PASS_SCORE}: improve it before committing"]
    return []


def run(p, path, html):
    return overlap(p, path) + sources(html) + local_detail(p, html) + review(p)

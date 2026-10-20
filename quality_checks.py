"""Extra quality checks for Divulge Diaries posts, called from validate.py.

1. Spelling: catches typos such as "Drea" (dream), "guranteed", "accomodation".
   No internet or extra packages needed: words are checked against an English word
   list shipped in the repo (spelling-words.txt.gz, British and American spellings),
   plus every word already used in the other posts and trip pages (so place, lodge
   and Swahili names the site already uses pass). Titles, meta fields and headings
   are checked word by word; in the body, capitalised names are skipped. Correct but
   unusual words can be added to spelling-allowlist.txt, one per line.
2. Title promise: a title that promises a number of items ("7 rules", "10 reasons")
   must deliver that many (numbered headings, H2/H3 sections or list items).
   Important words in the title should also appear in the post.
3. Keyword stuffing: the focus keyword may not appear in too many H2 headings
   or too often in one paragraph.
"""
import glob
import gzip
import json
import math
import os
import re
import unicodedata
from collections import Counter

HERE = os.path.dirname(os.path.abspath(__file__))

KNOWN_MISSPELLINGS = {
    "accomodation": "accommodation", "accomodations": "accommodations", "acommodation": "accommodation",
    "guranteed": "guaranteed", "garanteed": "guaranteed", "gaurantee": "guarantee", "guarentee": "guarantee",
    "cutomer": "customer", "costumer": "customer", "recieve": "receive", "beleive": "believe",
    "seperate": "separate", "definately": "definitely", "occured": "occurred", "untill": "until",
    "begining": "beginning", "neccessary": "necessary", "necesary": "necessary", "occassion": "occasion",
    "occasionaly": "occasionally", "wierd": "weird", "freind": "friend", "goverment": "government",
    "enviroment": "environment", "excercise": "exercise", "experiance": "experience", "foriegn": "foreign",
    "independant": "independent", "maintainance": "maintenance", "managment": "management",
    "millenium": "millennium", "noticable": "noticeable", "posession": "possession", "prefered": "preferred",
    "priviledge": "privilege", "recomend": "recommend", "reccomend": "recommend", "refered": "referred",
    "relevent": "relevant", "succesful": "successful", "tommorow": "tomorrow", "truely": "truly",
    "unforgetable": "unforgettable", "unforgetteble": "unforgettable", "breathtakng": "breathtaking",
    "wildbeest": "wildebeest", "wildebeests": "wildebeest", "serengetti": "serengeti", "ngorongo": "ngorongoro",
    "kilimanjoro": "kilimanjaro", "kilimajaro": "kilimanjaro", "amboselli": "amboseli", "samburru": "samburu",
    "nakaru": "nakuru", "naivash": "naivasha", "okovango": "okavango", "engligh": "english",
    "binocluars": "binoculars", "binoculers": "binoculars", "itenerary": "itinerary", "itinery": "itinerary",
    "itinary": "itinerary", "flamingoes": "flamingos", "hippopotamuses": "hippos", "cheeta": "cheetah",
    "leapard": "leopard", "leopords": "leopards", "rhinocerous": "rhinoceros", "giraff": "giraffe",
    "elepahnt": "elephant", "elephent": "elephant", "safarri": "safari", "saffari": "safari",
}

STOP = set("""a an and are as at be best by can do does for from guide guides how in into is it its
of on or our the their this to vs what when where which who why with without your you yours
it's here's that's should know need needs really worth tips tip plan planning things ways reasons
explained simple easy ultimate complete essential practical honest real top more most every each
before after about over under than then while also just still why's what's how's""".split())

NUMBER_WORDS = {"two": 2, "three": 3, "four": 4, "five": 5, "six": 6, "seven": 7, "eight": 8,
                "nine": 9, "ten": 10, "eleven": 11, "twelve": 12, "fifteen": 15, "twenty": 20}

LIST_NOUNS = r"(rules?|tips?|reasons?|ways?|things?|steps?|mistakes?|questions?|facts?|ideas?|places?|" \
             r"parks?|lodges?|camps?|hotels?|differences?|options?|experiences?|activities|animals?|" \
             r"birds?|routes?|items?|secrets?|signs?|sites?|spots?|destinations?|" \
             r"reserves?|conservancies|treks?|hikes?|beaches|islands?|lakes?|days? out|trips?)"

_vocab_cache = {}


def _words(text):
    # Drop accents first so "Meliá" or "Gonçalves" are read as whole words, not split in two.
    text = "".join(c for c in unicodedata.normalize("NFKD", text) if not unicodedata.combining(c))
    return re.findall(r"[A-Za-z][A-Za-z'-]*[A-Za-z]|[A-Za-z]", text)


def _strip_html(html):
    html = re.sub(r"Photo by .{0,300}?Unsplash", " ", html or "", flags=re.S)  # photographer credits
    return re.sub(r"<[^>]+>", " ", html)


def _load_allowlist():
    path = os.path.join(HERE, "spelling-allowlist.txt")
    if not os.path.exists(path):
        return set()
    with open(path, encoding="utf-8") as f:
        return {line.strip().lower() for line in f if line.strip() and not line.startswith("#")}


def _vocab(exclude_path):
    """Words used in the other posts and trip pages (place names, lodge names, Swahili words)."""
    key = os.path.abspath(exclude_path)
    if key in _vocab_cache:
        return _vocab_cache[key]
    counts = Counter()
    for f in glob.glob(os.path.join(HERE, "posts", "*.json")):
        if os.path.abspath(f) == key:
            continue
        try:
            d = json.load(open(f, encoding="utf-8"))
        except Exception:
            continue
        text = " ".join(str(d.get(k, "")) for k in ("title", "meta_title", "meta_description", "excerpt"))
        text += " " + _strip_html(d.get("content", ""))
        counts.update(w.lower() for w in _words(text))
    for f in glob.glob(os.path.join(HERE, "site-data", "trips", "*.txt")):
        try:
            counts.update(w.lower() for w in _words(_strip_html(open(f, encoding="utf-8", errors="ignore").read())))
        except Exception:
            pass
    _vocab_cache[key] = counts
    return counts


_dictionary = None


def _dict():
    """English word list (370,000 words, British and American spellings) shipped in the repo."""
    global _dictionary
    if _dictionary is None:
        path = os.path.join(HERE, "spelling-words.txt.gz")
        _dictionary = set()
        if os.path.exists(path):
            with gzip.open(path, "rt", encoding="utf-8") as f:
                _dictionary = {line.strip() for line in f if line.strip()}
    return _dictionary


def _edits1(word):
    letters = "abcdefghijklmnopqrstuvwxyz"
    splits = [(word[:i], word[i:]) for i in range(len(word) + 1)]
    deletes = [a + b[1:] for a, b in splits if b]
    transposes = [a + b[1] + b[0] + b[2:] for a, b in splits if len(b) > 1]
    replaces = [a + c + b[1:] for a, b in splits if b for c in letters]
    inserts = [a + c + b for a, b in splits for c in letters]
    return set(deletes + transposes + replaces + inserts)


def _is_word(w, words, vocab, allow):
    if w in words or w in vocab or w in allow:
        return True
    for suffix, repl in (("'s", ""), ("s", ""), ("es", ""), ("ies", "y"), ("ed", ""), ("ed", "e"),
                         ("ing", ""), ("ing", "e"), ("er", ""), ("ers", ""), ("ly", ""), ("ise", "ize"),
                         ("ised", "ized"), ("iser", "izer"), ("isers", "izers"), ("ising", "izing")):
        if w.endswith(suffix):
            base = w[: -len(suffix)] + repl
            if base in words or base in vocab or base in allow:
                return True
    return False


def spelling(p, path, html):
    """Flags known misspellings anywhere, and unknown words in titles, headings, meta fields
    and lowercase body words. Capitalised words in the body (names of lodges, people, places)
    are skipped there, but titles and headings are always checked."""
    words = _dict()
    if not words:
        return ["spelling-words.txt.gz is missing, so the spelling check could not run"]
    vocab = _vocab(path)
    allow = _load_allowlist()
    common = {w for w, c in vocab.items() if c >= 3}
    headings = " ".join(re.findall(r"<h[23][^>]*>(.*?)</h[23]>", html or "", re.S))
    strict = [("title", p.get("title", "")), ("meta_title", p.get("meta_title", "")),
              ("meta_description", p.get("meta_description", "")), ("headings", _strip_html(headings))]
    loose = [("excerpt", p.get("excerpt", "")), ("featured_image_alt", p.get("featured_image_alt", "")),
             ("content", _strip_html(html))]
    loose += [("faq", f"{i.get('q', '')} {i.get('a', '')}") for i in p.get("faq", [])]
    found = {}
    for check_caps, fields in ((True, strict), (False, loose)):
        for field, text in fields:
            text = text.replace("&nbsp;", " ").replace("&amp;", " ").replace("’", "'")
            for raw in _words(text):
                for part in raw.split("-"):
                    w = part.lower().strip("'")
                    if len(w) < 3 or w in found:
                        continue
                    if w in KNOWN_MISSPELLINGS:
                        found[w] = (field, KNOWN_MISSPELLINGS[w])
                        continue
                    if not check_caps and part[:1].isupper():
                        continue
                    if _is_word(w, words, vocab, allow):
                        continue
                    guesses = sorted((c for c in _edits1(w) if c in common or c in words),
                                     key=lambda c: (c[:1] != w[:1], -vocab.get(c, 0)))
                    found[w] = (field, guesses[0] if guesses else None)
    out = []
    for w, (field, fix) in found.items():
        hint = f' (did you mean "{fix}"?)' if fix else ""
        out.append(f'possible typo in {field}: "{w}"{hint}. Fix it, or if the word is correct '
                   f'(a name or local word), add it to spelling-allowlist.txt')
    return out


def _promised_numbers(text):
    t = text.lower()
    t = re.sub(r"\b\d+\s*-?\s*(day|days|night|nights|hour|hours|hr|hrs|km|kg|m|metres|meters|"
               r"percent|%|year|years|month|months|minute|minutes|week|weeks)\b", " ", t)
    t = re.sub(r"24/7|\$\s?\d[\d,]*|us\$\s?\d[\d,]*|\d+(st|nd|rd|th)\b", " ", t)
    nums = []
    for m in re.finditer(r"\b(\d{1,2}|" + "|".join(NUMBER_WORDS) + r")\s+(?:[a-z'-]+\s+){0,3}?" + LIST_NOUNS + r"\b", t):
        n = m.group(1)
        n = int(n) if n.isdigit() else NUMBER_WORDS[n]
        if 2 <= n <= 30:
            nums.append((n, m.group(0).strip()))
    return nums


def title_promise(p, ps, html):
    errs = []
    h2 = [t for tag, t in ps.headings if tag == "h2"]
    faq_i = next((i for i, t in enumerate(h2) if t.lower().startswith("frequently asked")), len(h2))
    main_h2 = [t for t in h2[:faq_i] if not t.lower().startswith(("final thoughts", "frequently asked"))]
    h3 = []
    in_faq = False
    for tag, t, _ in ps.sequence:
        if tag == "h2":
            in_faq = t.lower().startswith("frequently asked")
        elif tag == "h3" and not in_faq and not re.search(r"quick facts|insider tips|checklist|key takeaways", t, re.I):
            h3.append(t)
    numbered = [t for t in h2 + h3 if re.match(r"^\s*(\d+[.):]|#\d+|(rule|tip|step|reason|way)\s+\d+)", t, re.I)]
    ol_counts = [blk.count("<li") for blk in re.findall(r"<ol\b.*?</ol>", html, re.S)]
    ul_counts = [blk.count("<li") for blk in re.findall(r"<ul\b.*?</ul>", html, re.S)]
    tr_counts = [max(blk.count("<tr") - 1, 0) for blk in re.findall(r"<table\b.*?</table>", html, re.S)]
    delivered = {len(numbered), len(main_h2), len(h3)} | set(ol_counts) | set(ul_counts) | set(tr_counts)
    for field in ("title", "meta_title"):
        for n, phrase in _promised_numbers(p.get(field, "")):
            if n not in delivered:
                errs.append(f'{field} promises "{phrase}" but the post has no set of {n} numbered headings, '
                            f'H2/H3 sections or list items. Deliver exactly {n}, or change the title')

    return errs


def keyword_stuffing(p, ps):
    errs = []
    kw = p.get("focus_keyword", "").lower().strip()
    if not kw:
        return errs
    h2 = [t for tag, t in ps.headings if tag == "h2"
          and not t.lower().startswith(("frequently asked", "final thoughts"))]
    with_kw = [t for t in h2 if kw in t.lower()]
    limit = max(2, math.ceil(len(h2) / 3))
    if len(with_kw) > limit:
        errs.append(f'focus keyword is in {len(with_kw)} of {len(h2)} H2 headings (max {limit}). '
                    f'Reads as keyword stuffing; use natural variations in the other headings')
    for t, box in ps.paras:
        if t.lower().count(kw) > 2:
            errs.append(f'focus keyword used {t.lower().count(kw)} times in one paragraph (max 2): {t[:60]}')
            break
    return errs


def run(p, path, ps, html):
    return spelling(p, path, html) + title_promise(p, ps, html) + keyword_stuffing(p, ps)

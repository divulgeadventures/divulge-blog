# Market agent procedure (used by the 5 market agents, each runs DAILY from 25 Nov 2026)

Each agent's prompt gives its MARKET BRIEF (markets, currency, baseline, local angles) and its
GROUP id (us-canada, uk-ie-au-nz, dach-nl-be, fr-it-es or asia-gulf). Everything else is here.
Divulge Adventures content only: never mention any other business.

## 1. Set up
- Clone/pull github.com/divulgeadventures/divulge-blog.
- Read in full: TEMPLATE.md, CONTENT_MAP.md, POST_FORMAT.md, REVIEW.md, FIELD_NOTES.md,
  markets/queue-<GROUP>.md, markets/<GROUP>.md (your past reports, if it exists), REFRESH_QUEUE.md,
  topics-log.md, images-used.md. Load TOPIC_BANK.csv and site-data/posts.json with Python.

## 2. Pick the topic (from the queue, in priority order)
- Take the first row with status `todo` in markets/queue-<GROUP>.md.
- If it overlaps a published post (same search intent) or a REFRESH_QUEUE.md row, set its status to
  `skip (overlaps /<slug>/)` and take the next row. Follow the row's "Differentiate from" note.
- Keep the queue topped up: if 3 or fewer `todo` rows are left (before taking today's), first add
  6 new rows (same columns, next numbers), then write today's post as normal. New rows come from
  your competitor checks and Monday reports: gaps where competitors rank and we have no page,
  "People also ask" questions from your market, and seasonal topics 2-3 months ahead. Put the
  strongest first and never add a keyword an existing post already targets.

## 3. Competitor check (max 6 WebSearch/WebFetch calls; max 3 on non-Monday runs)
- Search the row's keyword (and 2-3 related ones) as a traveller from your markets would.
- Note who holds the top 5 and what they offer that we lack (local-currency prices, flights,
  reviews, depth, FAQs). Never name or link competitors in the post.

## 4. Write the post
- Exactly to the daily blog standard: TEMPLATE.md structure and box/table HTML; CONTENT_MAP.md rules
  for internal links (incl. 1-2 matching trip pages from site-data/trips.json), images (4+: owner
  photos from site-data/media.json first, then the Unsplash connector with premium:false only; never
  any photo credit, photographer name or Unsplash link; alt text only from the photo's description),
  1,800-2,300 words. Spelling per your brief (British English unless the brief says US).
- Research 6-10 searches. Every fee, visa, flight, health or distance figure is verified on an
  official or recent source, linked, with "(checked <Month YYYY>)". Link at least 2 different
  official/authoritative sources (park authority, government, health body, tourism board,
  conservation body). Never invent prices, reviews, quotes or stats; only quote Divulge prices
  found in site-data/trips/<slug>.txt.
- Local detail: use matching notes from FIELD_NOTES.md (list them in "field_notes_used") and owner
  photos wherever they fit.
- Owner SEO rules: focus keyword about 0.8-1% of words, never twice in one paragraph except the
  intro; SEO title under 60 characters starting with the keyword, H1 close in meaning but not
  identical; link the matching /destinations/<country>/ page within the first 200 words; no
  "not X but Y" openings or lists of exactly three in closings; no author bio box; no year in
  title or slug; all links as HTML <a href>.

## 5. Critic pass, save and validate
- Do the critic pass in REVIEW.md and add the "review" block. Score 8+ = "status": "publish";
  below 8 after one round of fixes = "status": "draft" (say why in the run log).
- Save as posts/market-YYYY-MM-DD-<slug>.json (today's Nairobi date), "publish_at" today at the
  next full hour after this run (Nairobi time, +03:00); if another post in posts/ already uses that
  hour today, take the next free hour. Run python3 validate.py on it until PASS (it enforces overlap, sources, local
  detail and review).
- Update: the queue row (`done YYYY-MM-DD https://divulgeadventures.com/<slug>/` or `draft ...`),
  CONTENT_MAP.md ("Published by the daily task" list), topics-log.md
  ("YYYY-MM-DD | MARKET <GROUP> | category | keyword | slug"), images-used.md, and TOPIC_BANK.csv
  if the keyword matches a row (mark it done).

## 6. Action report
Full report on MONDAYS only (on other days skip the competitor summary and just add one line at the
top of markets/<GROUP>.md: "YYYY-MM-DD: <post URL> (<keyword>), score N"). Monday report: add a dated entry at the top of markets/<GROUP>.md (create it with a one-line intro if missing;
keep the latest 12 entries): who ranks top 5 for the keywords checked and what they do better; our
gaps for these markets; 3-5 concrete to-dos for the owner (site changes, listings, reviews,
backlinks, partners, translations); the next queue row.

## 7. Commit
- run-log.md entry below the "---" line: "## YYYY-MM-DD HH:MM | Market agent <GROUP>" plus 2-5
  lines (post title + URL, status and publish time, review score, images owner/Unsplash,
  "Could not open: ...").
- Commit as user "divulgeadventures" / "divulgeadventures@gmail.com", message
  "Market post <GROUP> YYYY-MM-DD". git fetch origin main and rebase (keep both sides' lines on
  conflicts), push to main.
- Keep the run to about 30 minutes. Finish with a 4-line summary: post URL and status, main
  competitor insight, top 3 to-dos for the owner, next keyword.

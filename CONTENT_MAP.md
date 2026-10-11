# Divulge Diaries Content Map

Every new post fills a gap below. Never write a topic that duplicates or competes with an existing post (list at the bottom) or a money page.

## How to pick today's post (TOPIC_BANK.csv)

All new-post topics live in TOPIC_BANK.csv (about 1,900 topics, 300+ per destination). Columns: id, destination, priority (P1 > P2 > P3), score (higher first), focus_keyword, working_title, category, intent, topic_type, target_month, link_to (main money page), status, notes.

1. Owner ideas in TOPIC_IDEAS.md come first (oldest unchecked line). Add the idea to TOPIC_BANK.csv as a new row (next free id for its destination, priority P1) and mark it done when written.
2. Otherwise pick the destination from this 9-step cycle: Kenya, Tanzania, Uganda, Kenya, Botswana, Tanzania, Namibia, Kenya, Rwanda. Your step = (number of new-post lines in topics-log.md below the line "--- topic bank start ---") modulo 9, counting from 0 = Kenya. If that destination has no `todo` rows left, take the next destination in the cycle. The 3 posts of one day must be for 3 different destinations: if the cycle gives a destination already written today, take the next one.
3. Within the destination, choose the `todo` row in this order:
   a. Seasonal first, but at most ONE seasonal post per day: if no post written today (check today's posts in posts/) used a seasonal row, prefer a row whose target_month is 2 or 3 months after the current month (posts need time to rank before the season). Otherwise skip this step.
   b. Then rows whose notes say "Core topic from the original plan" (topic_type pillar before core).
   c. Then by priority (P1, P2, P3), then highest score, then lowest id.
4. The keyword and working title are a starting point: you may refine the focus keyword to the exact phrase people search (from your research) and you write your own title per TEMPLATE.md, but keep the row's topic and intent. Use the row's category and link to its link_to page (or a more specific trip on it).
5. Lodge rows: describe the property only from its official website. If you cannot confirm it is currently operating, set the row's status to `skip (could not verify)` and take the next row.
6. After writing, set the row's status to `done YYYY-MM-DD https://divulgeadventures.com/<slug>/`. If a row overlaps an existing post or a money page, set `skip (covered by <url>)` and take the next row. Edit the CSV with Python's csv module so every other row is preserved exactly.
7. When a destination has fewer than 30 `todo` rows, add 50 new rows for it from real "People also ask" and related-search questions, checked against existing posts and the bank.

Status values: `todo`, `done YYYY-MM-DD <url>`, `skip (<reason>)`.

## Money pages (link targets for the Trip CTA and in-body links)

- Kenya safaris: https://divulgeadventures.com/destinations/kenya-safari/
- Tanzania safaris: https://divulgeadventures.com/destinations/tanzania-safari/
- Rwanda gorilla trek: https://divulgeadventures.com/destinations/rwanda-gorilla-trek/
- Uganda safaris: https://divulgeadventures.com/destinations/uganda-safari/
- Botswana safaris: https://divulgeadventures.com/destinations/botswana-safari/
- Namibia safaris: https://divulgeadventures.com/destinations/namibia-safari/
- Safari deals: https://divulgeadventures.com/safari-deals-divulge-adventures/
- Birding safaris: https://divulgeadventures.com/activities/birding-safaris/
- Bush safaris: https://divulgeadventures.com/activities/bush-safaris-wildlife-tours/
- Wildlife photography safaris: https://divulgeadventures.com/activities/wildlife-photography-safaris/
- Bush & beach: https://divulgeadventures.com/activities/bush-beach-safaris/
- Fly-in safaris: https://divulgeadventures.com/activities/flight-packages-fly-in-safaris/
- Gorilla trekking safaris: https://divulgeadventures.com/activities/gorilla-trekking-safaris-tours/
- Team building events: https://divulgeadventures.com/activities/corporate-team-building-events/
- Short excursions & day trips: https://divulgeadventures.com/activities/short-excursions-day-trips/
- Mountain climbing: https://divulgeadventures.com/mountain-climbing-safari-packages/
- Day hikes & treks: https://divulgeadventures.com/trip-types/day-hikes-and-treks/
- Hiking & safari combo: https://divulgeadventures.com/trip-types/hiking-and-safari-combo/
- Travel insurance: https://divulgeadventures.com/travel-insurance-divulge-adventures/
- Flying Doctors: https://divulgeadventures.com/flying-doctors-services-divulge-adventures/
- Yellow fever requirements: https://divulgeadventures.com/yellow-fever-requirements-ke-tz/
- Proposal form: https://divulgeadventures.com/safari-proposal-request/

Individual trip pages: open the matching destination or activity page with WebFetch and link the best-matching trip.

## Original topic clusters

The original 8 clusters were moved into TOPIC_BANK.csv on 2026-10-08 (their unwritten rows are the "Core topic from the original plan" rows). Already written from them: kenya-safari-cost, great-migration-month-by-month, best-masai-mara-camps-by-budget, gorilla-trekking-in-rwanda. "mara river crossing best time" is covered by the refresh of great-wildebeest-migration.

## Rules

- Older posts are rewritten in place from REFRESH_QUEUE.md (one a day). Never write a new post on a topic that a REFRESH_QUEUE.md row targets; those rows' focus keywords are taken.
- Rwanda: the Rwanda destination page currently lists no Rwanda trips, so Rwanda posts send readers to the quote form (https://divulgeadventures.com/safari-proposal-request/) and WhatsApp in the Trip CTA instead of a trip page, until Rwanda trips appear on that page.

- Divulge Adventures content only. Never mention Hiking Outdoor Gear Hub or any other business, and do not write single-trail day-hike guides (those belong to a separate site). Mountain climbing posts are fine (Kilimanjaro, Mount Kenya).
- Before writing, check the existing-post list below and the live site search (https://divulgeadventures.com/?s=<keyword>) to avoid overlap. If a row turns out to overlap an existing post, mark it `skip (covered by <url>)` and take the next row.
- Pillars link to every done post in their cluster; supporting posts link to their pillar once it is done.
- Add every new post you publish to the "Existing posts" list below under the right category.

## Existing posts (for internal links; do not duplicate)

### Safari Planning Tips
- https://divulgeadventures.com/tanzania-safari-guide/ (Tanzania safari on a budget)
- https://divulgeadventures.com/why-choose-guided-namibia-safaris/ (guided vs self-drive Namibia)
- https://divulgeadventures.com/what-to-pack-for-tanzania-safari-checklist/
- https://divulgeadventures.com/kenya-safari-packing-list/
- https://divulgeadventures.com/masai-mara-safari-cost/
- https://divulgeadventures.com/masai-mara-entrance-fee/

### Great Migration & River Crossing
- https://divulgeadventures.com/serengeti-migration-ultimate-guide/
- https://divulgeadventures.com/great-wildebeest-migration/ (Mara migration timing)

### Accommodation Guide
- https://divulgeadventures.com/luxury-safari-lodges/
- https://divulgeadventures.com/masai-mara-sustainable-tourism/ (eco lodges in the Mara)

### Team Building
- https://divulgeadventures.com/chaka-ranch-team-building-2-days-1-night/
- https://divulgeadventures.com/best-team-building-venues-near-nairobi/
- https://divulgeadventures.com/nkasiri-adventure-park-team-building/
- https://divulgeadventures.com/burudani-adventure-park-team-building/

### Safari Themes
- https://divulgeadventures.com/divulgeadventures-com-kakamega-forest/
- https://divulgeadventures.com/lake-bogoria-geysers-flamingos-rift-valley/
- https://divulgeadventures.com/lake-nakuru-safari-guide/
- https://divulgeadventures.com/best-national-parks-to-visit-in-uganda/
- https://divulgeadventures.com/night-game-drives-in-kenya/
- https://divulgeadventures.com/masai-mara-balloon-safari/
- https://divulgeadventures.com/kenya-safari-by-train/
- https://divulgeadventures.com/ol-pejeta-safari-cottages/
- https://divulgeadventures.com/safe-family-safari-planning/
- https://divulgeadventures.com/tanzania-honeymoon/
- https://divulgeadventures.com/solo-female-safari/
- https://divulgeadventures.com/maasai-village-cultural-tour-tanzania/
- https://divulgeadventures.com/hot-air-balloon-safari/
- https://divulgeadventures.com/maasai-culture-village-visits-and-traditions/
- https://divulgeadventures.com/divulge-adventures-the-big-five-guide/

### Destination Tips
- https://divulgeadventures.com/best-national-parks-in-tanzania-divulge/
- https://divulgeadventures.com/nairobi-national-park-kenyas/
- https://divulgeadventures.com/zanzibar-beach-holidays-extension/
- https://divulgeadventures.com/conquering-mount-kilimanjaro/ (Machame route, altitude sickness)
- https://divulgeadventures.com/elephant-walking-safari/ (Tarangire)
- https://divulgeadventures.com/ngorongoro-crater-wildlife/
- https://divulgeadventures.com/southern-circuit-tanzania-safari-itinerary/
- https://divulgeadventures.com/first-time-namibia-safari-mistakes/
- https://divulgeadventures.com/best-time-of-year-to-visit-tanzania-safari/
- https://divulgeadventures.com/ultimate-namibia-safari-guide/
- https://divulgeadventures.com/chimpanzee-trekking-mahale-mountains/
- https://divulgeadventures.com/namibia-safari-tips-101-travel-tips/
- https://divulgeadventures.com/ol-pejeta-conservancy-2/
- https://divulgeadventures.com/tsavo-west-national-park/
- https://divulgeadventures.com/samburu-national-game-reserve/
- https://divulgeadventures.com/amboseli-national-park-2/
- https://divulgeadventures.com/lake-nakuru-national-park/
- https://divulgeadventures.com/julius-nyerere-national-park/
- https://divulgeadventures.com/ruaha-national-park/
- https://divulgeadventures.com/tarangire-national-park/
- https://divulgeadventures.com/lake-manyara-national-park/
- https://divulgeadventures.com/ngorongoro-national-park/
- https://divulgeadventures.com/serengeti-national-park-2/
- https://divulgeadventures.com/olare-motorogi-conservancy/
- https://divulgeadventures.com/siana-conservancy-maasai-mara/
- https://divulgeadventures.com/lemek-conservancy-safari/
- https://divulgeadventures.com/mara-naboisho-conservancy/
- https://divulgeadventures.com/ol-kinyei-conservancy-kenya/
- https://divulgeadventures.com/nashulai-masai-conservancy/
- https://divulgeadventures.com/mara-triangle-2/
- https://divulgeadventures.com/mara-north-conservancy-safari/
- https://divulgeadventures.com/mara-triangle/
- https://divulgeadventures.com/best-time-to-visit-masai-mara/
- https://divulgeadventures.com/masai-mara-national-reserve-experience/ (reserve vs conservancy)
- https://divulgeadventures.com/best-attractions-in-uganda/
- https://divulgeadventures.com/lake-nakuru-national-park-attractions/
- https://divulgeadventures.com/tsavo-east-national-park-attractions/
- https://divulgeadventures.com/8-best-of-amboseli-national-park-attractions/
- https://divulgeadventures.com/discovering-laikipia-wilderness/
- https://divulgeadventures.com/serengeti-national-park/
- https://divulgeadventures.com/samburu-game-reserve-kenya/
- https://divulgeadventures.com/solio-game-reserve/
- https://divulgeadventures.com/amboseli-national-park/
- https://divulgeadventures.com/ol-pejeta-conservancy/

### Published by the daily task
(append new posts here: - URL (focus keyword))
- https://divulgeadventures.com/kenya-safari-cost/ (kenya safari cost)
- https://divulgeadventures.com/great-migration-month-by-month/ (great migration month by month)
- https://divulgeadventures.com/best-masai-mara-camps-by-budget/ (masai mara camps)
- https://divulgeadventures.com/gorilla-trekking-in-rwanda/ (gorilla trekking in rwanda)
- https://divulgeadventures.com/kenya-safari-in-december/ (kenya safari in december)
- https://divulgeadventures.com/tanzania-northern-circuit-itinerary/ (tanzania northern circuit itinerary)
- https://divulgeadventures.com/uganda-safari-in-december/ (uganda safari in december)
- https://divulgeadventures.com/day-trips-from-nairobi/ (day trips from nairobi)
- https://divulgeadventures.com/botswana-in-december/ (botswana in december)

# Divulge Diaries Content Map

Every new post fills a gap below. Never write a topic that duplicates or competes with an existing post (list at the bottom) or a money page.

## Site data copy (read this instead of opening divulgeadventures.com)

A GitHub Action copies the site into `site-data/` twice a day (05:45 and 13:45 Nairobi time), so runs never need permission to open the site:
- `site-data/media.json`: every Media Library image (id, url, alt, title, date). Search it with Python, e.g. find entries whose url/alt/title contain "amboseli".
- `site-data/posts.json` and `site-data/pages.json`: every published post and page (title, url) for overlap checks and internal links.
- `site-data/trips.json`: every trip (id, slug, url, title, featured image); `site-data/trips/<slug>.txt`: the full text of each trip page (itinerary, lodges, price, includes, trip facts) and its images.
- `site-data/snapshot.json`: when the copy was made and any errors.
Only open divulgeadventures.com with WebFetch if the copy is missing what you need or is more than 2 days old, and never wait on a permission request for it.

## How to pick today's post (TOPIC_BANK.csv)

All new-post topics live in TOPIC_BANK.csv (about 1,900 topics, 300+ per destination). Columns: id, destination, priority (P1 > P2 > P3), score (higher first), focus_keyword, working_title, category, intent, topic_type, target_month, link_to (main money page), status, notes.

1. Owner ideas in TOPIC_IDEAS.md come first, at most ONE per day during the group sprint (see 3.G) (oldest unchecked line). Add the idea to TOPIC_BANK.csv as a new row (next free id for its destination, priority P1) and mark it done when written.
2. Otherwise pick the destination from this 9-step cycle: Kenya, Tanzania, Uganda, Kenya, Botswana, Tanzania, Namibia, Kenya, Rwanda. Your step = (number of new-post lines in topics-log.md below the line "--- topic bank start ---") modulo 9, counting from 0 = Kenya. If that destination has no `todo` rows left, take the next destination in the cycle. The posts of one day must be for different destinations: if the cycle gives a destination already written today, take the next one.
3. Within the destination, choose the `todo` row in this order:
   G. GROUP SPRINT (owner's decision 12 Nov 2026, runs until no `todo` rows with topic_type `group` are left): EVERY post of the day is a group post, except at most one owner idea per day from TOPIC_IDEAS.md. During the sprint the daily target is 9 new posts, published hourly at 10:00-18:00 Nairobi time (owner asked to maximise the scheduled runs; finish by about 21 Nov 2026), one per destination where possible; if a destination has no group rows left, use a destination with the most group rows left. Take this destination's `todo` group row with the lowest id. When all group rows are done, go back to one group post per day (if no post written today used a group row) and delete this sprint sentence. If this destination has no group rows left, take the next destination in the cycle that has one (keeping today's posts in different destinations where possible). Group rows' notes say how to link: the row's group sub-page (link_to and 'Main link' in the notes) is the main internal link, plus /group-safaris/ once, the orange CTA button to https://divulgeadventures.com/group-safari-inquiry/ instead of the quote form, and 1-2 matching trips. Write for the group organiser: group sizes, vehicles and rooming, group pricing logic, safety, logistics, and why a private group trip beats joining strangers.
   0. Comparison boost (about one every 2-3 days): if no post written today or yesterday used a row whose notes start with "BOOST" (look up the row ids in topics-log.md), and this destination has a `todo` row whose notes start with "BOOST", take it (lowest id first) before steps a-c. Rows whose notes start with "DEMOTED" are left until everything else for that destination is done.
   a. Seasonal first, but at most ONE seasonal post per day: if no post written today (check today's posts in posts/) used a seasonal row, prefer a row whose target_month is 2 or 3 months after the current month (posts need time to rank before the season). Otherwise skip this step.
   b. Then rows whose notes say "Core topic from the original plan" (topic_type pillar before core).
   c. Then by priority (P1, P2, P3), then highest score, then lowest id.
4. The keyword and working title are a starting point: you may refine the focus keyword to the exact phrase people search (from your research) and you write your own title per TEMPLATE.md, but keep the row's topic and intent. Use the row's category and link to its link_to page (or a more specific trip on it).
5. Lodge rows: describe the property only from its official website. If you cannot confirm it is currently operating, set the row's status to `skip (could not verify)` and take the next row.
6. After writing, set the row's status to `done YYYY-MM-DD https://divulgeadventures.com/<slug>/`. If a row overlaps an existing post or a money page, set `skip (covered by <url>)` and take the next row. Edit the CSV with Python's csv module so every other row is preserved exactly.
7. When a destination has fewer than 30 `todo` rows, add 50 new rows for it from real "People also ask" and related-search questions, checked against existing posts and the bank.

Status values: `todo`, `done YYYY-MM-DD <url>`, `skip (<reason>)`.

### Search demand (re-scored 13 Nov 2026)

Priorities and scores of `todo` rows were re-set from Google Keyword Planner (US + UK, the buyers' markets). Each re-scored row's notes end with "| Demand US+UK (Keyword Planner, Nov 2026): …" giving the measured or estimated monthly searches. What the sample showed:
- Real demand (100–10,000 a month): park and place names (Arusha National Park, Moremi, Nyungwe, Queen Elizabeth, Lewa), named lodges and camps, Kigali and Windhoek hotels/restaurants, country money matters (currency, visa on arrival), Tanzania + Zanzibar combos. These are now P1/P2.
- Almost no demand (under 10 a month): "how to get to X from Y", "X map and gates", "X 3 day itinerary", "N day X itinerary", "where to see <animal> in X", "X safari from <country>", park entrance fees and minor-place "best time / things to do / where to stay". These are now P3 and are written last.
- Group, core, pillar, team, migration and day-trip rows and BOOST/DEMOTED rows were not changed.
When you refine a focus keyword (step 4), prefer the phrase with real searches; when a P3 row's topic fits as a section of a P1/P2 post, cover it there instead and mark the P3 row `skip (covered by <url>)`.

## Images (4+ per post)

- The owner's WordPress Media Library has a few hundred real Divulge photos. Use them first. Search `site-data/media.json` (see "Site data copy" above) using short words: the park or place (e.g. "Amboseli", "Okavango", "Nyungwe"), the animal ("lion", "elephant", "gorilla"), and the country or trip name ("Botswana safari", "Moremi").
- An item is usable when its title or alt text names the post's place, animal, lodge or a Divulge trip in the same destination (e.g. "Moremi Khwai and Savuti" for a Botswana post). Write alt text from what the title says; for a trip-named photo say e.g. "on safari in Botswana" rather than guessing details.
- Never use items titled "image" or similar, screenshots, logos, icons, maps, or anything whose title suggests people are the subject (guests, clients, team, staff, group, family, couple, wedding, names).
- Owner photos may appear in different posts, but not in two posts on the same day and preferably not within 14 days (images-used.md).
- Fill any remaining slots with the Unsplash connector (search_photos, short query such as "Amboseli elephants" or "Okavango delta", orientation landscape, plus "none"). Only use results with "premium": false. Read each result's description and alt_description: name a specific place in the alt text only if the description names it, and never use a photo whose description names a different place for a place-specific spot (e.g. a Serengeti photo in an Amboseli section). Use urls.regular as the src (keep its parameters).
- No photo credits (owner's rule, 6 November 2026): never add a credit line, photographer name, "Photo by"/"via Unsplash" text, or any link to an Unsplash or photographer profile under any image. Wherever an image gets a filename or title, name it after its subject (e.g. lion-masai-mara.jpg).
- Place each image after the first paragraph of its section (never straight after a heading), so the answer under each heading stays first.
- If the Unsplash connector is unavailable, fall back to WebSearch "unsplash <subject>" as before.

## Money pages (link targets for the Trip CTA and in-body links)

- Kenya safaris: https://divulgeadventures.com/destinations/kenya-safari/
- Tanzania safaris: https://divulgeadventures.com/destinations/tanzania-safari/
- Rwanda gorilla trek: https://divulgeadventures.com/destinations/rwanda-gorilla-trek/
- Uganda safaris: https://divulgeadventures.com/destinations/uganda-safari/
- Botswana safaris: https://divulgeadventures.com/destinations/botswana-safari/
- Namibia safaris: https://divulgeadventures.com/destinations/namibia-safari/ (owns the keyword "namibia safari": no post may target it; posts link here with anchors like "Namibia safari packages")
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

Individual trip pages: pick the best-matching trip from `site-data/trips.json` (title and url) and link it.

## Original topic clusters

The original 8 clusters were moved into TOPIC_BANK.csv on 2026-10-08 (their unwritten rows are the "Core topic from the original plan" rows). Already written from them: kenya-safari-cost, great-migration-month-by-month, best-masai-mara-camps-by-budget, gorilla-trekking-in-rwanda. "mara river crossing best time" is covered by the refresh of great-wildebeest-migration.

## Rules

- Older posts are rewritten in place from REFRESH_QUEUE.md (one a day). Never write a new post on a topic that a REFRESH_QUEUE.md row targets; those rows' focus keywords are taken.
- Rwanda: the Rwanda destination page currently lists no Rwanda trips, so Rwanda posts send readers to the quote form (https://divulgeadventures.com/safari-proposal-request/) and WhatsApp in the Trip CTA instead of a trip page, until Rwanda trips appear on that page.

- Divulge Adventures content only. Never mention Hiking Outdoor Gear Hub or any other business, and do not write single-trail day-hike guides (those belong to a separate site). Mountain climbing posts are fine (Kilimanjaro, Mount Kenya).
- Before writing, check the existing-post list below and `site-data/posts.json` (every published post title) to avoid overlap. If a row turns out to overlap an existing post, mark it `skip (covered by <url>)` and take the next row.
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
- https://divulgeadventures.com/twin-rivers-tigoni-team-building/
- https://divulgeadventures.com/chaka-ranch-team-building-2-days-1-night/
- https://divulgeadventures.com/best-team-building-venues-near-nairobi/
- https://divulgeadventures.com/nkasiri-adventure-park-team-building/
- https://divulgeadventures.com/burudani-adventure-park-team-building/

### Safari Themes
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
- https://divulgeadventures.com/lake-bogoria-geysers-flamingos-rift-valley/ (lake bogoria)
- https://divulgeadventures.com/divulgeadventures-com-kakamega-forest/
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
- https://divulgeadventures.com/ngorongoro-crater-lodges/ (ngorongoro crater lodges)
- https://divulgeadventures.com/namibia-hiking-safari/ (namibia hiking safari)
- https://divulgeadventures.com/kenya-safari-in-january/ (kenya safari in january)
- https://divulgeadventures.com/golden-monkey-trekking-rwanda/ (golden monkey trekking)
- https://divulgeadventures.com/best-serengeti-camps/ (serengeti camps)
- https://divulgeadventures.com/serengeti-in-december/ (serengeti in december)
- https://divulgeadventures.com/bwindi-gorilla-trekking/ (bwindi gorilla trekking)
- https://divulgeadventures.com/kenya-honeymoon-safari/ (kenya honeymoon safari)
- https://divulgeadventures.com/okavango-delta-vs-chobe/ (okavango delta vs chobe)
- https://divulgeadventures.com/kenya-eta/ (kenya eta)
- https://divulgeadventures.com/tanzania-visa/ (tanzania visa)
- https://divulgeadventures.com/east-africa-tourist-visa/ (east africa tourist visa)
- https://divulgeadventures.com/kenya-safari-vaccinations/ (kenya safari vaccinations)
- https://divulgeadventures.com/tanzania-park-fees/ (tanzania park fees)
- https://divulgeadventures.com/rwanda-gorilla-permit/ (rwanda gorilla permit)
- https://divulgeadventures.com/kenya-park-fees/ (kenya park fees)
- https://divulgeadventures.com/botswana-vs-namibia/ (botswana vs namibia)
- https://divulgeadventures.com/is-kenya-safe-for-safari/ (is kenya safe for safari)
- https://divulgeadventures.com/serengeti-in-january/ (serengeti in january)
- https://divulgeadventures.com/best-time-to-visit-namibia/ (best time to visit namibia)
- https://divulgeadventures.com/tipping-in-kenya/ (tipping in kenya)
- https://divulgeadventures.com/rwanda-vs-tanzania-safari/ (rwanda vs tanzania safari)
- https://divulgeadventures.com/tanzania-safari-in-december/ (tanzania safari in december)
- https://divulgeadventures.com/tanzania-safari-in-january/ (tanzania safari in january)
- https://divulgeadventures.com/chimpanzee-trekking-in-kibale/ (chimpanzee trekking in kibale)
- https://divulgeadventures.com/masai-mara-vs-amboseli/ (masai mara vs amboseli)
- https://divulgeadventures.com/botswana-in-january/ (botswana in january)
- https://divulgeadventures.com/best-time-to-climb-kilimanjaro/ (best time to climb kilimanjaro)
- https://divulgeadventures.com/namibia-in-december/ (namibia in december)
- https://divulgeadventures.com/7-day-kenya-safari-itinerary/ (kenya safari itinerary)
- https://divulgeadventures.com/how-hard-is-gorilla-trekking/ (how hard is gorilla trekking)
- https://divulgeadventures.com/samburu-vs-masai-mara/ (samburu vs masai mara)
- https://divulgeadventures.com/kenya-and-tanzania-safari/ (kenya and tanzania safari)
- https://divulgeadventures.com/uganda-in-january/ (uganda in january)
- https://divulgeadventures.com/kenya-vs-uganda-safari/ (kenya vs uganda safari)
- https://divulgeadventures.com/botswana-safari-guide/ (botswana safari)
- https://divulgeadventures.com/kilimanjaro-routes/ (kilimanjaro routes)
- https://divulgeadventures.com/namibia-in-january/ (namibia in january)
- https://divulgeadventures.com/amboseli-lodges/ (amboseli lodges)
- https://divulgeadventures.com/rwanda-in-december/ (rwanda in december)
- https://divulgeadventures.com/lake-naivasha-lodges/ (lake naivasha lodges)
- https://divulgeadventures.com/serengeti-vs-ngorongoro/ (serengeti vs ngorongoro)
- https://divulgeadventures.com/gorilla-trekking-packing-list/ (gorilla trekking packing list)
- https://divulgeadventures.com/samburu-lodges/ (samburu lodges)
- https://divulgeadventures.com/best-time-to-visit-botswana/ (best time to visit botswana)
- https://divulgeadventures.com/lemosho-vs-machame/ (lemosho vs machame)
- https://divulgeadventures.com/etosha-safari/ (etosha safari)
- https://divulgeadventures.com/best-time-to-visit-kenya/ (best time to visit kenya)
- https://divulgeadventures.com/rwanda-in-january/ (rwanda in january)
- https://divulgeadventures.com/kenya-birding-safari/ (kenya birding safari)
- https://divulgeadventures.com/serengeti-calving-season/ (serengeti calving season)
- https://divulgeadventures.com/bwindi-vs-volcanoes/ (bwindi vs volcanoes)
- https://divulgeadventures.com/kenya-safari-in-february/ (kenya safari in february)
- https://divulgeadventures.com/chobe-safari/ (chobe safari)
- https://divulgeadventures.com/serengeti-safari-cost/ (serengeti safari cost)
- https://divulgeadventures.com/etosha-vs-okavango/ (etosha vs okavango)
- https://divulgeadventures.com/serengeti-golf-course/ (serengeti golf course)
- https://divulgeadventures.com/naivasha-corporate-retreat/ (naivasha corporate retreat)
- https://divulgeadventures.com/kilimanjaro-group-climb/ (kilimanjaro group climb)
- https://divulgeadventures.com/uganda-in-february/ (uganda in february)
- https://divulgeadventures.com/namugongo-pilgrimage/ (namugongo pilgrimage)
- https://divulgeadventures.com/first-kenya-safari/ (first kenya safari)
- https://divulgeadventures.com/botswana-in-february/ (botswana in february)
- https://divulgeadventures.com/charity-kilimanjaro-climb/ (charity kilimanjaro climb)
- https://divulgeadventures.com/namibia-in-february/ (namibia in february)
- https://divulgeadventures.com/elephant-orphanage-and-giraffe-centre/ (elephant orphanage and giraffe centre)
- https://divulgeadventures.com/rwanda-study-tour/ (rwanda study tour)
- https://divulgeadventures.com/twin-rivers-tigoni-team-building/ (twin rivers tigoni)
- https://divulgeadventures.com/tanzania-school-trip/ (tanzania school trip)
- https://divulgeadventures.com/gorilla-trekking-vs-habituation/ (gorilla trekking vs habituation)
- https://divulgeadventures.com/kenya-school-trip/ (kenya school trip)
- https://divulgeadventures.com/okavango-delta-safari/ (okavango delta safari)
- https://divulgeadventures.com/serengeti-in-february/ (serengeti in february)
- https://divulgeadventures.com/namibia-self-drive-convoy/ (namibia self drive convoy)
- https://divulgeadventures.com/east-africa-dmc/ (east africa dmc)
- https://divulgeadventures.com/rwanda-school-trip/ (rwanda school trip)
- https://divulgeadventures.com/serengeti-field-trip/ (serengeti field trip)
- https://divulgeadventures.com/group-gorilla-trekking-uganda/ (group gorilla trekking)
- https://divulgeadventures.com/botswana-photography-safari-groups/ (botswana photography safari)
- https://divulgeadventures.com/university-study-tour-kenya/ (university study tour in kenya)
- https://divulgeadventures.com/okavango-field-course/ (okavango field course)
- https://divulgeadventures.com/namibia-photography-tour/ (namibia photography tour)
- https://divulgeadventures.com/kenya-safari-dmc/ (kenya safari dmc)
- https://divulgeadventures.com/kigali-conference-gorilla-trekking/ (kigali conference)
- https://divulgeadventures.com/tanzania-incentive-trip/ (tanzania incentive trip)
- https://divulgeadventures.com/uganda-educational-tour/ (uganda educational tour)
- https://divulgeadventures.com/botswana-family-safari/ (botswana family safari)
- https://divulgeadventures.com/stargazing-in-namibia/ (stargazing in namibia)
- https://divulgeadventures.com/masai-mara-incentive-trip/ (masai mara incentive trip)
- https://divulgeadventures.com/exclusive-use-safari-camp-botswana/ (exclusive use safari)
- https://divulgeadventures.com/tanzania-family-safari/ (tanzania family safari)
- https://divulgeadventures.com/tanzania-ground-handler/ (tanzania ground handler)
- https://divulgeadventures.com/kenya-family-reunion-safari/ (family reunion safari)
- https://divulgeadventures.com/rwanda-corporate-incentive-trip/ (rwanda corporate incentive trip)
- https://divulgeadventures.com/uganda-primate-field-course/ (primate field course)
- https://divulgeadventures.com/namibia-geology-field-trip/ (namibia geology field trip)
- https://divulgeadventures.com/botswana-incentive-trip/ (botswana incentive trip)
- https://divulgeadventures.com/church-group-trip-to-kenya/ (church group trip)
- https://divulgeadventures.com/birding-in-uganda-for-groups/ (birding in uganda)
- https://divulgeadventures.com/tanzania-mission-trip/ (tanzania mission trip)

# Refresh queue (older Divulge Diaries posts)

One older post is rewritten in place each day (same URL and publish date) and brought fully up to TEMPLATE.md. Work top to bottom: take the first row whose status is `todo`.

Columns: slug | focus keyword | category | notes | status

- Read the live post first (WebFetch https://divulgeadventures.com/<slug>/). Keep accurate, useful facts (verify them), drop anything vague, wrong, padded or dated.
- Remove any year from the title (e.g. "2026 Migration Forecast" becomes a timeless title). Mention a year only next to a dated fact such as a fee "(checked <Month YYYY>)".
- Remove any link written as plain text or in markdown style (for example "[https://...](https://...)" or a bare URL in a sentence) and replace it with a proper HTML link with descriptive anchor text. Remove links to pages that do not exist (check with WebFetch).
- Fix words that run together around links or full stops (e.g. "world.However", "theMasai Mara", "Parkoffers") and remove stray characters or headings pasted into paragraphs.
- "Differentiate" notes say how this post must differ from a similar post so the two do not compete in Google.
- Status values: `todo`, `done YYYY-MM-DD`.

## Part 0: broken links and Search Console quick wins (do these first, top to bottom)

These posts already rank on Google (Rank Math Analytics, 8 October 2026). Keep each post aimed at the search it already ranks for: use that exact phrase as the focus keyword and work it into the title, first sentence and an H2. Write a title and meta description that make searchers want to click (a clear benefit, a number, the current season), because ranking without clicks is wasted.

| slug | focus keyword | category | notes | status |
|---|---|---|---|---|
| divulgeadventures-com-kakamega-forest | kakamega forest | Destination Tips | FIX FIRST: the live post shows raw markdown-style links as text (e.g. "[https://...](https://...)" and a Kenya Forest Service URL in brackets) and links to /kakamega-forest-kenya-safari-guide/, which does not exist. Rewrite with proper <a href> links (descriptive anchor text, Kenya Forest Service as the external authority link). Was filed under Safari Themes: move to Destination Tips. | todo |
| lake-bogoria-geysers-flamingos-rift-valley | lake bogoria | Destination Tips | FIX FIRST: the live post shows 8 raw markdown-style links as text, several ending in "?utm_source=gemini", and links to itself; replace with proper <a href> links with descriptive anchor text and remove the self-links and tracking parameters. Was filed under Safari Themes: move to Destination Tips. | todo |
| lake-nakuru-safari-guide | lake nakuru day trip from nairobi | Safari Themes | FIX FIRST: the live post has 10 link problems: link code inside the "Recommended Itineraries" heading and its table-of-contents entry, raw markdown links, bare "www.lakenakurukenya.com" text with "+ 1"/"+ 2" citation leftovers, and links to /safari-national-parks/kenya-national-parks/lake-nakuru-national-park/ (check it exists; if not, link /lake-nakuru-national-park/). Day trip / overnight from Nairobi: timings, route, combine with Naivasha. Link the park guide. | todo |
| discovering-laikipia-wilderness | laikipia wilderness | Destination Tips | Already position 8 for "laikipia wilderness" (86 impressions, 0 clicks): the priority is a much more clickable title and meta description. Keep the topic broad (Laikipia region, conservancies, wildlife, black leopards, getting there, when to go). Do not change the URL. | done 2026-10-08 |
| masai-mara-safari-cost | masai mara safari cost | Safari Planning Tips | Ranks 30-40 for "masai mara cost", "masai mara prices" and "masai mara price": use those exact phrasings in headings and the FAQ (e.g. an H2 "Masai Mara Safari Prices Per Person"). Mara-specific costs (fees, camps by tier, road vs fly-in). Differentiate from /kenya-safari-cost/ (whole-country) and link it. | todo |
| best-attractions-in-uganda | attractions in uganda | Destination Tips | Ranks 23-27 for "attractions in uganda" and "uganda attractions" and climbing: use both phrasings. Non-park attractions too (Jinja/Source of the Nile, Lake Bunyonyi, Kampala, Sipi Falls). Link the Uganda parks post. | todo |
| masai-mara-sustainable-tourism | eco lodge masai mara | Accommodation Guide | Ranks 15 for "eco lodge masai mara": use that phrase. No year in title. | todo |
| hot-air-balloon-safari | hot air balloon safari | Safari Themes | Ranks 29 for "safari hot air balloon": keep this post GENERAL (balloon safaris across East Africa: Masai Mara, Serengeti, and others, what is included, cost ranges, how to choose) and link the Mara-specific balloon post. Use the phrase "safari hot air balloon" too. | todo |
| masai-mara-balloon-safari | masai mara hot air balloon safari | Safari Themes | LINK FIX: two bare URLs written in sentences (governorsballoonsafari.com, maasaimara.com) and a broken word "aasai" next to the second; replace with proper links and fix the text. Mara balloon only: launch sites, times, what's included, cost range (verify). Differentiate from the general /hot-air-balloon-safari/ post and link it. | todo |
| kenya-safari-by-train | madaraka express safari | Safari Themes | LINK FIX: three bare URLs written in sentences (savetheelephants.org, kws.go.ke, metickets.krc.co.ke); replace with proper links with descriptive anchor text. SGR to Tsavo: stations, classes, booking (verify on official site). | todo |
| night-game-drives-in-kenya | night game drives in kenya | Safari Themes | LINK FIX: two bare URLs written in sentences (kws.go.ke, an Ol Pejeta tariff PDF); replace with proper links with descriptive anchor text. Where allowed (conservancies, specific parks), what you see. | todo |
| mara-naboisho-conservancy | mara naboisho conservancy | Destination Tips | LINK FIX: raw link text "[https://divulgeadventures.com/masai-mara-national-reserve/](...)" visible in the sentence "Located adjacent to the famous"; replace with a proper link to the Reserve vs Conservancy post. | todo |
| kenya-safari-packing-list | kenya safari packing list | Safari Planning Tips | TEXT FIX: raw formula code is visible to readers ("300\mathrm{mm}+" and "15{kg}"); write plain "300mm+" and "15kg". Include a downloadable-style checklist table, luggage limits on light aircraft (verify), clothing colours. | todo |
| luxury-safari-lodges | luxury safari lodges in kenya | Accommodation Guide | LINK FIX: the Four Seasons link carries "utm_source=google" tracking parameters (remove them) and "luxury safari" link runs into "lodges" with no space. Kenya luxury lodges and camps by area, from official sites only; no unverified rates. | todo |

## Part 1: duplicate topics (owner decision: leave as they are)

The owner has decided to keep these ten posts unchanged. Do NOT refresh, redirect or delete any of them, never add them back to this queue (including under "After the queue is done"), and do not ask the owner to redirect them. Linking to them from other posts is fine.

- /amboseli-national-park/ and /amboseli-national-park-2/
- /serengeti-national-park/ and /serengeti-national-park-2/
- /ol-pejeta-conservancy/ and /ol-pejeta-conservancy-2/
- /mara-triangle/ and /mara-triangle-2/
- /samburu-national-game-reserve/ and /samburu-game-reserve-kenya/

## Part 2: high-value posts

| slug | focus keyword | category | notes | status |
|---|---|---|---|---|
| masai-mara-entrance-fee | masai mara entrance fee | Safari Planning Tips | Fees change: verify current Narok County / Mara Triangle fees on official sources, show per season and per visitor type in a table, say when checked. | todo |
| best-time-to-visit-masai-mara | best time to visit masai mara | Destination Tips | Month-by-month Mara conditions beyond the migration (green season, prices, crowds, rain). Link /great-migration-month-by-month/ for herd movements. | todo |
| great-wildebeest-migration | mara river crossing | Great Migration & River Crossing | Refocus on Mara River crossings: when (months, time of day), where (crossing points), how to see one, patience and luck, reserve rules at crossings. Current title has a year: replace. Link /great-migration-month-by-month/. Also mark the Cluster 2 row "mara river crossing best time" in CONTENT_MAP.md as done with this URL. | todo |
| serengeti-migration-ultimate-guide | serengeti migration | Great Migration & River Crossing | Tanzania side only: calving in Ndutu, Grumeti, northern Serengeti/Kogatende. Link the pillar and /serengeti-national-park/. | todo |
| what-to-pack-for-tanzania-safari-checklist | what to pack for tanzania safari | Safari Planning Tips | Tanzania specifics (Ngorongoro cold mornings, Kilimanjaro add-on, Zanzibar). Differentiate from the Kenya packing list and link it. | todo |
| best-time-of-year-to-visit-tanzania-safari | best time to visit tanzania | Destination Tips | Month-by-month table by park (Serengeti, Ngorongoro, Tarangire, southern parks, Zanzibar). | todo |
| best-national-parks-in-tanzania-divulge | best national parks in tanzania | Destination Tips | Comparison table (park, best for, best months, how to reach). Link each park post. | todo |
| best-national-parks-to-visit-in-uganda | best national parks in uganda | Destination Tips | Was filed under Safari Themes: move to Destination Tips. Title is all caps: fix. Parks only; differentiate from /best-attractions-in-uganda/. | todo |
| masai-mara-national-reserve-experience | masai mara reserve vs conservancy | Destination Tips | Comparison table (crowds, night drives, walking, off-road, cost). Link each conservancy post. | todo |
| tanzania-safari-guide | tanzania safari on a budget | Safari Planning Tips | Budget tactics (camping, shoulder season, group joining, shorter circuits); verified fees. | todo |
| divulge-adventures-the-big-five-guide | big five animals | Safari Themes | Where to see each of the Big Five in Kenya, Tanzania, Uganda, Rwanda, Botswana, Namibia (table). | todo |
| nairobi-national-park-kenyas | nairobi national park | Destination Tips | Half-day and full-day options, gates, fees, combining with the Sheldrick and Giraffe Centre. | todo |
| zanzibar-beach-holidays-extension | zanzibar beach holiday after safari | Destination Tips | How to add Zanzibar (flights from Arusha/Serengeti, nights, beaches by area). | todo |

## Part 3: park, conservancy and theme posts

| slug | focus keyword | category | notes | status |
|---|---|---|---|---|
| 8-best-of-amboseli-national-park-attractions | things to do in amboseli | Destination Tips | Differentiate: activities list (Observation Hill, swamps, Maasai visit, photography spots). Link /amboseli-national-park/. | todo |
| lake-nakuru-national-park | lake nakuru national park | Destination Tips | Full park guide. Differentiate from the two other Lake Nakuru posts. | todo |
| lake-nakuru-national-park-attractions | things to do in lake nakuru | Destination Tips | Activities list (Baboon Cliff, Makalia Falls, rhino sanctuary). Link the park guide. | todo |
| tsavo-west-national-park | tsavo west national park | Destination Tips | Full guide (Mzima Springs, Shetani lava, rhino sanctuary, SGR access). | todo |
| tsavo-east-national-park-attractions | tsavo east national park | Destination Tips | Full guide incl. attractions (Lugard Falls, Aruba Dam, Yatta Plateau). | todo |
| ngorongoro-national-park | ngorongoro crater safari | Destination Tips | Crater guide (descent rules and time limits, fees, where to stay on the rim). Differentiate from the photography post. | todo |
| ngorongoro-crater-wildlife | ngorongoro crater photography | Safari Themes | Photography angle only. Link the crater guide. | todo |
| tarangire-national-park | tarangire national park | Destination Tips | Full park guide. Link /elephant-walking-safari/ for walking. | todo |
| elephant-walking-safari | tarangire walking safari | Safari Themes | Walking safari angle only. | todo |
| lake-manyara-national-park | lake manyara national park | Destination Tips | | todo |
| ruaha-national-park | ruaha national park | Destination Tips | | todo |
| julius-nyerere-national-park | nyerere national park | Destination Tips | Boat safaris on the Rufiji, fly-in access. | todo |
| southern-circuit-tanzania-safari-itinerary | southern tanzania safari | Destination Tips | Itinerary table combining Nyerere and Ruaha. | todo |
| conquering-mount-kilimanjaro | kilimanjaro machame route | Destination Tips | Machame route day by day, altitude sickness prevention (cite NHS/CDC). | todo |
| chimpanzee-trekking-mahale-mountains | mahale chimpanzee trekking | Destination Tips | Access (fly-in), best months, permit rules (verify). | todo |
| ultimate-namibia-safari-guide | namibia safari | Destination Tips | Namibia pillar: link every other Namibia post. | todo |
| namibia-safari-tips-101-travel-tips | namibia travel tips | Safari Planning Tips | Practical tips (driving distances, fuel, cash, water, seasons). | todo |
| first-time-namibia-safari-mistakes | first time namibia safari | Safari Planning Tips | Mistakes angle only. | todo |
| why-choose-guided-namibia-safaris | namibia self drive vs guided | Safari Planning Tips | Comparison table. | todo |
| olare-motorogi-conservancy | olare motorogi conservancy | Destination Tips | | todo |
| mara-north-conservancy-safari | mara north conservancy | Destination Tips | | todo |
| lemek-conservancy-safari | lemek conservancy | Destination Tips | | todo |
| siana-conservancy-maasai-mara | siana conservancy | Destination Tips | | todo |
| ol-kinyei-conservancy-kenya | ol kinyei conservancy | Destination Tips | | todo |
| nashulai-masai-conservancy | nashulai conservancy | Destination Tips | | todo |
| solio-game-reserve | solio game reserve | Destination Tips | | todo |
| ol-pejeta-safari-cottages | ol pejeta safari cottages | Accommodation Guide | From the property's official site only. | todo |
| safe-family-safari-planning | family safari in kenya | Safari Themes | Age limits at camps, child-friendly parks, malaria advice (cite CDC/NHS). | todo |
| tanzania-honeymoon | tanzania honeymoon | Safari Themes | Differentiate from the Kenya honeymoon pillar. | todo |
| solo-female-safari | solo female safari | Safari Themes | | todo |
| maasai-village-cultural-tour-tanzania | maasai village tour tanzania | Safari Themes | Tanzania only. | todo |
| maasai-culture-village-visits-and-traditions | maasai village visit kenya | Safari Themes | Kenya only. Link the Tanzania post. | todo |

## Not in the queue

- Team Building posts (Chaka Ranch, best venues near Nairobi, Nkasiri, Burudani): written recently, refresh later.

## After the queue is done

Start again from the top with posts last refreshed more than 6 months ago, updating fees, seasons and links to newer posts. Never include the ten Part 1 posts.

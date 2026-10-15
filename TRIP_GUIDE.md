# Trip Optimiser guide (WP Travel Engine trip pages)

Goal: make every trip page on divulgeadventures.com rank on Google for its own specific search and get cited by AI answers. One JSON file per trip goes in `trips/`; the Trip Optimiser snippet on the site imports new or changed files within about 5 minutes. Blog posts (`posts/`) are a separate job, so never touch them here.

## Each run (2 trips, about 30 minutes in total)

1. `git pull`. Open TRIP_QUEUE.md and take the first 2 rows with status `todo`. If none are left, write "Trip queue finished" in run-log.md, commit, push and stop.
2. For each trip:
   1. **Read the trip from the site copy:** `site-data/trips.json` gives the trip's `id`, `url`, `destination` and current featured image (url + alt). `site-data/trips/<slug>.txt` holds the full page text: overview, day-by-day itinerary with the lodge for each night, includes/excludes, price (the FIRST "From $x $y" line = regular then sale price; later "Duration … From" lines belong to related trips), trip facts, and the page's images at the end (gallery photos first; small 374x226 / 410x250 files are related-trip cards, ignore them). Use the full-size Media Library url from `site-data/media.json` for each photo. If the slug is missing from the copy or the copy is more than 2 days old (`site-data/snapshot.json`), WebFetch `https://divulgeadventures.com/trip/<slug>/` instead; if that also fails, leave the row `todo` and take the next one.
   2. Keep the page's real itinerary, lodges, price and inclusions. The importer never changes the itinerary. Never invent prices, lodges, reviews, awards or statistics. Note every typo, conflicting lodge name and wrong fact for the run log.
   3. The snapshot only updates twice a day, so a trip you optimise today still shows its old overview in the copy until the next snapshot; that is expected.
   4. Write the file (rules below), then run `python3 validate_trip.py trips/<file>.json` and fix everything marked FAIL.
   5. Update the trip's row in TRIP_QUEUE.md: ID, `done YYYY-MM-DD`, keyword, file name.
3. Add a run-log.md entry (format below), then commit as divulgeadventures / divulgeadventures@gmail.com and push to main (`git pull --rebase` first if the push is rejected).

Do not retry pages that ask for permission or time out. Note them in the run log and move on. If the live trip page itself can't be opened, do NOT write that trip from memory: leave it `todo`, log "could not open the trip page" and try the next row. If no trip page opens at all, log it and stop.

## File format (copy the existing files exactly)

File name: `trips/<destination>-<id>-<keyword-in-hyphens>.json`, for example `kenya-8080-7-day-masai-mara-river-crossing-safari.json`. The file is a JSON list with ONE object:

`id` (number), `slug` (the current slug; it is only changed on unpublished trips), `focus_keyword`, `rank_math_title`, `rank_math_desc`, `post_content`, `overview`, `highlights`, `faq`, `featured_image` {url, alt}, `gallery` [{url, alt}], `status` ("publish").

See `trips/kenya-8080-7-day-masai-mara-river-crossing-safari.json` as the model.

## Rules

- **Focus keyword:** one unique, specific phrase per trip, as people search it: duration + places + type, for example "7 day masai mara river crossing safari" or "4 day lake nakuru and masai mara safari". Use at least 4 words, never broad terms like "kenya safari" or "masai mara safari", and never a keyword already used by another trip (`trips/*.json`) or a blog post (`posts/*.json`). The validator checks this. Do not put "luxury" in the keyword unless the page's lodges are genuinely luxury.
- **SEO title:** at most 60 characters and starts with the keyword. **Meta description:** at most 160 characters, includes the keyword and one concrete selling point (nights in a park, what's included, or "From USD x" if the page shows a price).
- **post_content:** one sentence that summarises the trip and includes the keyword.
- **Overview (HTML):**
  - Opens with a 40-70 word factual summary containing the keyword: duration, parks and nights, best months (as on the page), price "from" (only as shown on the page, otherwise "price on request"), and who it suits.
  - Then H2 sections, using the Mara River Crossing file as the pattern:
    - Why choose a [keyword]?
    - What to expect on the [keyword] (a short day-by-day summary with the page's drive times and lodges)
    - an H3 answering a real question (best month / is X days enough / fitness)
    - What is included (includes and excludes from the page)
    - How much does the [keyword] cost? (the green price box)
  - Paragraphs stay under 110 words and open by naming the subject. No bold inside paragraphs; bold labels in lists are fine.
- **Price box:** copy it exactly from the model files.
  - Page shows a price: "From USD [sale price] per adult (regular price USD [x])".
  - Page shows no price: the "Price on request" box from the Rwanda files.
  - The box always includes the WhatsApp link (wa.me/254706223888 with the trip title in the text), the email address and the quote form link https://divulgeadventures.com/safari-proposal-request/.
- **Links:**
  - 2-3 links to related Divulge Diaries guides from CONTENT_MAP.md "Existing posts", for example great-migration-month-by-month, best-masai-mara-camps-by-budget, kenya-safari-cost, best-time-to-visit-masai-mara, or a park guide. Make sure each guide exists on the list.
  - Plus the quote form, and optionally one related trip page (`/trip/<slug>/`).
  - All links are hyperlinked text, never raw URLs.
- **Highlights:** 4-6 short, concrete lines from the itinerary.
- **FAQ:** 4-5 questions specific to the trip. Cover:
  - best months
  - what's included
  - accommodation (lodges named on the page)
  - fitness / kids / families (or altitude for treks)
  - how to book
  Answers are 40-80 words each, start with a direct answer and repeat the trip's subject. For Mt Kenya treks, cover the route, altitude, fitness and the best months instead.
- **Images:** a featured image plus 4-6 gallery photos. The import REPLACES the trip's whole gallery, so:
  - Include every good owner photo already in the trip's gallery, with corrected alt text. Drop only photos that show the wrong place.
  - Then add owner photos from the Media Library for the parks in the itinerary. Search `site-data/media.json` for the park or animal (match url, alt and title). Owner photos have file names like `divulge_adventures…`, `Divulge-Adventures-…`, `IMG_…` or `DSC_…`. Files named after blog posts (e.g. `kenya-safari-cost.jpg`) came from Unsplash, so skip them.
  - Use the full `source_url`, not a resized `-600x810` copy.
  - Fill any gaps with the Unsplash connector (search_photos, `plus: "none"`, landscape, `premium: false` only, urls.regular as the url). Only use photos whose description matches the place.
  - No photo where a person is the main subject.
  - If any Unsplash photo is used, end the overview with this credit line:
    `<p style="font-size:12px;color:#777777;">Some gallery photos by <a href="[user.links.html]?utm_source=divulge_adventures&utm_medium=referral" target="_blank" rel="noopener">[name]</a> on <a href="https://unsplash.com/?utm_source=divulge_adventures&utm_medium=referral" target="_blank" rel="noopener">Unsplash</a>.</p>`
- **Alt text:** every image needs accurate alt text, and the featured image alt starts with the keyword. You cannot see owner photos, so describe them from their file name, Media Library title and existing alt. Name a place only if one of those names it, and never copy obviously wrong alt text (e.g. "B2B Agent Rates", "Safar").

## Run log entry (newest first, under the "---" line in run-log.md)

```
## YYYY-MM-DD HH:MM Trip Optimiser
- Done: <trip title> (ID, keyword, file), <trip title> (...)
- Photos: <n owner + n Unsplash> per trip
- Owner to fix by hand (the importer cannot change these):
  - <trip>: <itinerary typos, conflicting lodge names across days, "Luxury" in the title without luxury lodges, prices that differ between trips, missing prices, wrong trip facts, slug typos>
- Could not open: <pages/links, or "none">
- Next: <next 2 trips in the queue>
```

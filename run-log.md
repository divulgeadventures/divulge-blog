# Daily run log

Every run of the Divulge daily blog task adds a short entry at the TOP of this list (newest first): when it ran, what it did, the post title and link, and any links or pages it could not open. A day with fewer than 4 entries means some runs did not finish.

---

## 2026-10-15 08:47 Trip Optimiser
- Done: none. No trip page could be opened, so no trip files were written (the guide forbids writing trips from memory).
- Photos: none (no trips written).
- Owner to fix by hand (the importer cannot change these): nothing new this run; the items in the 2026-10-14 Trip Optimiser entry are still open (including the "mout-kemya" slug typo).
- Could not open: https://divulgeadventures.com/wp-json/wp/v2/trip?slug=sopa-lodges-circuit-amboseli-to-mara, https://divulgeadventures.com/wp-json/wp/v2/trip?slug=9-day-hells-gate-to-masai-mara-safari, https://divulgeadventures.com/trip/sopa-lodges-circuit-amboseli-to-mara/, https://divulgeadventures.com/trip/9-day-hells-gate-to-masai-mara-safari/, https://divulgeadventures.com/trip/amboseli-nakuru-masai-mara-safari/, https://divulgeadventures.com/trip/4-day-naivasha-nakuru-masai-mara-safari/ (all permission requests timed out; not retried). All four rows stay todo. Fix: approve divulgeadventures.com for web fetches in the scheduled task, or the task will keep stopping here.
- Next: sopa-lodges-circuit-amboseli-to-mara, 9-day-hells-gate-to-masai-mara-safari.

## 2026-10-15 07:25 | New post 1
East Africa Tourist Visa: Cost, Rules and How to Apply — https://divulgeadventures.com/east-africa-tourist-visa/
Uganda (owner idea "East Africa Tourist Visa", bank row UG-305), Safari Planning Tips. Scheduled to publish 2026-10-15 10:00 Nairobi time. Checked October 2026 on official pages: US$100, 90 days, multiple entry, first-entry rule, no extensions (Uganda immigration), Rwanda IremboGov "V11 East Africa Visa", Rwanda US$50 cap with fee waiver for AU/Commonwealth/Francophonie, Uganda single-entry US$50. Post tells Kenya-first travellers to confirm with Kenya Immigration because Kenya's online system now issues eTAs. Trip link /trip/gorilla-and-chimpanzee-trekking-safari/ taken from TRIP_QUEUE.md (not opened).
Images: 0 from the owner's Media Library (search could not be opened), 5 from Unsplash (featured Rwenzori hills, plus Kigali at sunset, Nyamirambo in Kigali, silverback gorilla, elephant herd).
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=east+africa+tourist+visa, the media library search (permission requests timed out; overlap checked against CONTENT_MAP.md), https://www.gov.uk/foreign-travel-advice/kenya/entry-requirements and https://www.gov.uk/foreign-travel-advice/uganda/entry-requirements (permission requests timed out).

## 2026-10-14 19:04 | Refresh
Masai Mara Hot Air Balloon Safari: Times, Costs and Honest Tips — https://divulgeadventures.com/masai-mara-balloon-safari/
Kenya, Safari Themes (REFRESH_QUEUE LINK FIX row). Rewritten in full on "masai mara hot air balloon safari": no bare URLs or broken words remain; Governors Balloon Safaris is now a proper link with anchor text; links the general /hot-air-balloon-safari/ post to differentiate. Facts checked October 2026 (Governors' FAQ and Where We Fly pages, a Mara lodge's published balloon rates); prices given as an estimated US$450-600 per adult range. Divulge's own balloon add-on prices (USD 480 vs USD 500 on two trip pages) were not quoted because they conflict.
Images: 0 from the owner's Media Library (search could not be opened), 4 from Unsplash (balloons at dawn at Little Governors' Camp, hippos on the Mara River, elephants with balloons at sunrise, zebras in the Mara). Featured image unchanged.
Skipped topics: none. Next eligible refresh row: kenya-safari-by-train (LINK FIX).
Could not open: https://divulgeadventures.com/masai-mara-balloon-safari/ (live post; permission request timed out, so the rewrite could not compare against the old text) and the media library search (permission request timed out).

## 2026-10-14 15:09 | New post 3
Tanzania Visa for Safari: Cost, eVisa Steps and Entry Rules — https://divulgeadventures.com/tanzania-visa/
Tanzania (owner idea "Tanzania visa for safari", bank row TZ-009), Safari Planning Tips. Scheduled to publish 2026-10-14 18:00 Nairobi time. Focus keyword refined to "tanzania visa"; TZ-314 (tanzania e visa) and TZ-319 (tanzania visa for tourists) set to skip as covered by this post. Checked October 2026: US$50 single entry for most nationalities, US$100 multiple entry for US citizens (Irish US$100), visa on arrival single-entry only (GOV.UK), new mandatory US$44 mainland inbound insurance from 1 October 2026 (GN 256 of 2026). Trip link /trip/12-days-magical-kenya-tanzania-safari/ taken from TRIP_QUEUE.md (destination page could not be opened to confirm it).
Images: 0 from the owner's Media Library (search could not be opened), 6 from Unsplash (featured giraffes in the Serengeti, plus Serengeti sunset, safari vehicle with buffalo, rhinos in Ngorongoro Crater, giraffes under a balloon, Zanzibar beach).
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=tanzania+visa, https://divulgeadventures.com/destinations/tanzania-safari/ and the media library search (permission requests timed out; overlap checked against CONTENT_MAP.md and repo posts).

## 2026-10-14 11:45 | Trip Optimiser (first run, done in chat)
- Done: 7-Day Luxury Mara River Crossing Safari (ID 8080, "7 day masai mara river crossing safari", kenya-8080-7-day-masai-mara-river-crossing-safari.json); 6-Day Best of Amboseli & Masai Mara Safari (ID 7364, "6 day amboseli and masai mara safari", kenya-7364-6-day-amboseli-and-masai-mara-safari.json).
- Photos: 7 owner + 0 Unsplash per trip (featured + 6 gallery), wrong alt texts replaced.
- Owner to fix by hand (the importer cannot change these):
  - Mara River Crossing: lodge names conflict across days (Day 1-2 Praro Amboseli Safari Camp vs a Sopa link on Day 2; Days 5-6 Zebra River Luxury Camp vs PrideInn Mara Camp), so pick one per night. Typos: "Engligh", "Germany" (should be German), "Accomodation", "Fullboard" (Full board). Title says "Luxury" but lodges are 4-star (Sopa, PrideInn): rename to "7-Day Mara River Crossing Safari" or switch to luxury camps. Highlights/trip facts list Lake Naivasha as a park, but it is only a boat ride stop. A photo in the gallery is named "serengeti-wildebeest-migration-river-crossing" (fine for a Mara River crossing, but check it is not a Tanzania-only shot).
  - Best of Amboseli & Mara: no price shown on the page (shows "Price on request" now; add a price if you have one). Typos: "Full Day at Amboseli national Park", "4 hours. drive.", "Germany", "Accomodation", "Prideinn" (PrideInn). Day 4 morning drive text says "an adventurous evening". Balloon price is USD 500 here but USD 480 on the Mara River Crossing page.
  - Site-wide: Mt Kenya trip slug is "mout-kemya" (typo; fix with a 301 redirect).
- Could not open: none.
- Next: sopa-lodges-circuit-amboseli-to-mara, 9-day-hells-gate-to-masai-mara-safari.

## 2026-10-14 11:20 | New post 2
Kenya eTA for Tourists: Cost, Documents and How to Apply — https://divulgeadventures.com/kenya-eta/
Kenya (owner idea "Kenya eTA for tourists", bank row KE-018), Safari Planning Tips. Scheduled to publish 2026-10-14 14:00 Nairobi time. Focus keyword refined to "kenya eta". Checked October 2026: eTA from US$30 (+US$100 expedited), ~3 working days; new mandatory Kenyan inbound travel health insurance (US$44 premium, from early October 2026, foreign policies not accepted per GOV.UK). eTA validity/single-entry terms not stated (could not confirm on an official source). Links the Kenya destination, travel insurance, Flying Doctors and yellow fever pages (no Kenya trip page could be opened).
Images: 0 from the owner's Media Library (search could not be opened), 6 from Unsplash (featured passport with stamps, plus passports with boarding passes, stamped passport, Masai Mara zebras, safari vehicle in the Mara, elephants at a Mara waterhole).
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=kenya+eta, https://divulgeadventures.com/destinations/kenya-safari/ and the media library search (permission requests timed out; overlap checked against CONTENT_MAP.md and repo posts), https://www.fragomen.com/insights/kenya-travel-health-insurance-requirement-implemented.html (fetch error).

## 2026-10-14 07:13 | New post 1
Okavango Delta vs Chobe: Which Botswana Safari Is Right for You? — https://divulgeadventures.com/okavango-delta-vs-chobe/
Botswana (bank row BW-056, BOOST comparison), Destination Tips. Scheduled to publish 2026-10-14 10:00 Nairobi time. Park fees: Botswana postponed its planned 1 April 2026 increase and kept existing rates (checked October 2026); no fee figures stated because published figures conflict. Links the Chobe-Khwai-Okavango and 8-day Moremi trip pages (trip links taken from earlier posts; destination page could not be opened).
Images: 0 from the owner's Media Library (search could not be opened), 6 from Unsplash (featured elephant in the Okavango, plus Okavango River aerial, Chobe River elephants, elephants crossing water in the Okavango, big cat in the Okavango, elephants in Chobe NP).
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=okavango+chobe, https://divulgeadventures.com/destinations/botswana-safari/, the media library search and https://whc.unesco.org/en/list/1432 (permission requests timed out; overlap checked against CONTENT_MAP.md and repo posts).

## 2026-10-13 19:10 | Refresh
Lake Nakuru Day Trip from Nairobi: Timings, Fees and What You'll See — https://divulgeadventures.com/lake-nakuru-safari-guide/
Kenya, Safari Themes. Rewritten fully to the template (FIX FIRST row): all raw markdown links, the link code in the itinerary heading, the bare "www.lakenakurukenya.com" text and "+ 1"/"+ 2" leftovers are gone; the park-guide link now points to /lake-nakuru-national-park/. Focus keyword set to "lake nakuru day trip" (the full "...from Nairobi" phrase cannot reach the required keyword density without stuffing; "from Nairobi" stays in the title, meta title and first sentence). KWS fees (US$90 non-resident adult, US$45 child) checked October 2026. Featured image unchanged.
Images: 0 from the owner's Media Library (not searched, see below), 4 from Unsplash (Lake Nakuru shore, zebras in Lake Nakuru NP, Lake Nakuru water, pelican on Lake Naivasha).
Skipped topics: none. Next FIX row: masai-mara-balloon-safari (LINK FIX).
Could not open: https://divulgeadventures.com/lake-nakuru-safari-guide/ (old post; permission request timed out, so it was rewritten from fresh research), https://en.wikipedia.org/wiki/Lake_Nakuru_National_Park and https://www.kws.go.ke/lake-nakuru-national-park (permission timeouts); the media library search and short-excursions page were not tried because they have timed out on every run today.

## 2026-10-13 15:08 | New post 3
Kenya Honeymoon Safari: Where to Go, Best Time, Costs and Itineraries — https://divulgeadventures.com/kenya-honeymoon-safari/
Kenya (bank row KE-002, core pillar, about 2,700 words), Safari Themes. Scheduled to publish 2026-10-13 18:00 Nairobi time. Fees checked October 2026: Masai Mara US$100/US$200 per adult per day (Jan-Jun/Jul-Dec), KWS Amboseli and Lake Nakuru US$90, Nairobi NP and Tsavo US$80, Kisite-Mpunguti US$25, Kenya eTA US$30; cost tiers labelled as estimates. No Kenya trip page could be opened, so the post links the Kenya destination, bush & beach and fly-in pages.
Images: 0 from the owner's Media Library (search could not be opened), 6 from Unsplash (featured Mara sunset, plus Mara plains, Mara tented camp, elephants below Kilimanjaro, Diani Beach, Lamu dhow).
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=kenya+honeymoon, https://divulgeadventures.com/destinations/kenya-safari/, the media library search and https://www.flysafarilink.com/baggage/ (permission requests timed out; overlap checked against CONTENT_MAP.md and repo posts).

## 2026-10-13 11:20 | New post 2
Bwindi Gorilla Trekking: Permits, Sectors, Costs and Tips — https://divulgeadventures.com/bwindi-gorilla-trekking/
Uganda (bank row UG-001, core topic), Destination Tips. Scheduled to publish 2026-10-13 14:00 Nairobi time. Focus keyword refined to "bwindi gorilla trekking". Permit US$800, habituation US$1,500 (US$1,800 from 1 Jan 2027) and the reduced US$600 April/May/November permit taken from recent published summaries of the UWA 2026-2028 tariff (checked October 2026; readers told to confirm). Links the Bwindi, 9-day and 10-day Uganda trip pages.
Images: 0 from the owner's Media Library (search could not be opened), 6 stock (Unsplash: 5 mountain gorillas in Bwindi, 1 Lake Bunyonyi), featured image included.
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=bwindi+gorilla+trekking, https://divulgeadventures.com/destinations/uganda-safari/, the media library search and https://ugandawildlife.org/conservation-tariffs/ (permission requests timed out; overlap checked against CONTENT_MAP.md and repo posts, trip links taken from earlier posts).

## 2026-10-13 07:18 | New post 1
Serengeti in December: Where the Herds Are, Weather and Costs — https://divulgeadventures.com/serengeti-in-december/
Tanzania (bank row TZ-047, seasonal pick for December), Great Migration & River Crossing, country tag: Tanzania. Scheduled to publish 2026-10-13 10:00 Nairobi time. Fees checked October 2026: Serengeti US$82.60 adult / US$23.60 child (high season July to mid-March), NCAA US$70.80 + US$59 concession. No Tanzania trip page could be opened, so the post links the Tanzania destination page and fly-in packages page.
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=serengeti+in+december, https://divulgeadventures.com/destinations/tanzania-safari/ and the media library search (permission requests timed out; overlap checked against CONTENT_MAP.md and repo posts), https://www.tanzaniaparks.go.tz/ (permission timed out; fees taken from a March 2026 published fee table matching earlier checks), three Unsplash photo pages (permission timeouts; not used).

## 2026-10-12 19:15 | Refresh
Lake Bogoria: Hot Springs, Geysers and Flamingos in Kenya's Rift Valley — https://divulgeadventures.com/lake-bogoria-geysers-flamingos-rift-valley/
Kenya, moved from Safari Themes to Destination Tips, country tag: Kenya. Fully rewritten to the template: the old raw markdown links, "utm_source=gemini" links and self-links are gone; external link is the Ramsar site record. The post now tells readers honestly that rising water has submerged most geysers (warden quoted by Nation, 2025; NASA 2020) and that the old main gate and Emsos road are underwater (July 2026). Entry fee not stated (could not verify on an official source; readers told we confirm it when quoting). New in-content image: flamingos at Lake Nakuru (Unsplash); featured image unchanged.
Skipped topics: none.
Could not open: https://divulgeadventures.com/lake-bogoria-geysers-flamingos-rift-valley/ and its wp-json version, https://divulgeadventures.com/destinations/kenya-safari/, the media library search (permission requests timed out; old post could not be read, so it was rewritten from fresh research and existing trip links in the repo), https://lakebogoria.org/entrance-fees/, /visitor-info/ and /plan-your-perfect-trip-to-lake-bogoria-national-reserve/ (fetch errors), https://whc.unesco.org/en/list/1060 and Unsplash/Pexels search pages (permission timeouts), Baringo County Finance Act 2023 PDF (unreadable binary).

## 2026-10-12 15:20 | New post 3
Best Serengeti Camps: Where to Stay by Area, Season and Budget — https://divulgeadventures.com/best-serengeti-camps/
Tanzania (bank row TZ-003, core topic), Accommodation Guide, country tag: Tanzania. Scheduled to publish 2026-10-12 18:00 Nairobi time. Focus keyword refined to "serengeti camps". Camps described only from official sources (Four Seasons press fact sheet, Serena Hotels pages, Asilia's own camp comparison, Ndutu Safari Lodge site); no room rates quoted, cost tiers labelled as estimates. Fees (Serengeti US$82.60, NCAA US$70.80 + US$59 concession) checked October 2026.
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=serengeti+camps and https://divulgeadventures.com/destinations/tanzania-safari/ (permission requests timed out; overlap checked against CONTENT_MAP.md and repo posts), https://www.fourseasons.com/serengeti/, https://www.serenahotels.com/serengeti (first attempt), https://www.asiliaafrica.com/east-africa/camps-lodges/sayari/, https://www.ndutu.com/ (first attempt) (permission timeouts; official info then read via other official pages), one Unsplash safari-tent photo page (fetch error; not used).

## 2026-10-12 11:20 | New post 2
Golden Monkey Trekking in Rwanda: Permits, Costs and What to Expect — https://divulgeadventures.com/golden-monkey-trekking-rwanda/
Rwanda (bank row RW-001, core topic), Destination Tips. Scheduled to publish 2026-10-12 14:00 Nairobi time. Focus keyword refined to "golden monkey trekking" (the exact phrase is too awkward to repeat at the required density). Permit rates (US$100 foreign adult, min age 12, 07:00 Kinigi, max 16 per group) checked October 2026 on the RDB booking portal. Links to the two Divulge golden monkey trip pages; Trip CTA goes to the quote form per the Rwanda rule.
Images: no golden monkey photo found on Unsplash/Pexels, so both images are mountain gorillas in Volcanoes National Park, Rwanda (alt text says so). Owner may want to swap in a golden monkey photo.
Skipped topics: none.
Could not open: https://divulgeadventures.com/destinations/rwanda-gorilla-trek/, the media library search and https://visitrwandabookings.rdb.rw/rdbportal/golden-monkey-tracking (permission requests timed out), https://visitrwanda.com/interests/golden-monkey-trekking/ (fetch error), Pexels search (blocked by robots), two Unsplash pages (fetch error / permission timeout; not used).

## 2026-10-12 07:13 | New post 1
Kenya Safari in January: Weather, Wildlife, Costs and Where to Go — https://divulgeadventures.com/kenya-safari-in-january/
Kenya (bank row KE-096, seasonal pick for January), Destination Tips. Scheduled to publish 2026-10-12 10:00 Nairobi time. Fees checked October 2026: Masai Mara US$100 Jan-Jun (Kenya Times, Aug 2026), KWS parks from the official KWS conservation fee sheet, eTA US$30.
Skipped topics: none.
Could not open: https://divulgeadventures.com/?s=kenya+safari+in+january, https://divulgeadventures.com/destinations/kenya-safari/, https://divulgeadventures.com/kenya-safari-in-december/ and the media library (permission requests timed out; used the repo's post files and CONTENT_MAP.md for overlap checks and links, and an already-linked Kenya birding trip page), https://fitfortravel.nhs.uk/destinations/africa/kenya (permission timed out; URL confirmed in search results), one Unsplash photo page (elephant herd; not used).

## 2026-10-11 18:58 | Refresh
Kakamega Forest: A Practical Guide to Kenya's Last Rainforest — https://divulgeadventures.com/divulgeadventures-com-kakamega-forest/
Kenya, moved from Safari Themes to Destination Tips. Rewritten fully to the template: the raw markdown links and the links to non-existent pages (/kakamega-forest-kenya-safari-guide/ and similar) are removed, there are proper links to KWS and Kenya Forest Service, KWS fees were checked October 2026, and the existing kakamega-forest-6.jpg image is reused.
Skipped topics: none.
Could not open: https://kws.go.ke/park/kakamega-forest-national-reserve/ (permission request timed out; used the same KWS page on sandbox.kws.go.ke instead), https://www.kenyaforestservice.org/ (permission request timed out; linked as the official KFS site), BirdLife DataZone Kakamega factsheet (page loaded empty), The Standard article on Kakamega forests (body not available).

## 2026-10-11 15:03 | New post 3
Namibia Hiking Safari: The Best Hikes, Seasons and Costs — https://divulgeadventures.com/namibia-hiking-safari/
Namibia (bank row NA-313), Safari Themes. Scheduled to publish 2026-10-11 18:00 Nairobi time.
Skipped topics: none.
Could not open: https://divulgeadventures.com/trip-types/hiking-and-safari-combo/ (permission request timed out; linked as a listed money page), https://www.nwr.com.na/wp-content/uploads/2021/06/Hiking-Precautions.pdf (blocked by robots), images.unsplash.com image files (could not test-load from the sandbox; photo pages confirmed).

## 2026-10-11 14:10 | Setup
Run log started. Posts so far from the topic bank: Kenya Safari in December, Northern Circuit Tanzania Itinerary, Uganda Safari in December, Day Trips from Nairobi, Botswana in December, Ngorongoro Crater Lodges.

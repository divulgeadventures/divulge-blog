# Trip Optimiser queue

Work top to bottom: the scheduled task takes the first 2 rows with status `todo` each run (see TRIP_GUIDE.md).
Status values: `todo`, `done YYYY-MM-DD`, `skip (<reason>)`. When you finish a trip, fill in its ID, status, keyword and file.
The first 10 Kenya rows are in the owner's priority order. Rwanda trips were optimised earlier and are not in this list.
`4-day-wildebeest-migration-safari`: confirmed as a Tanzania trip on 2026-10-23 (destination term 31, breadcrumb "Trips > Tanzania", Serengeti itinerary) and moved to the top of the Tanzania block.

| # | Destination | Trip slug (https://divulgeadventures.com/trip/<slug>/) | ID | Status | Focus keyword | File |
|---|---|---|---|---|---|---|
| 1 | Kenya | 7-day-luxury-mara-river-crossing-safari | 8080 | done 2026-10-14 | 7 day masai mara river crossing safari | kenya-8080-7-day-masai-mara-river-crossing-safari.json |
| 2 | Kenya | best-of-amboseli-masai-mara-safari | 7364 | done 2026-10-14 | 6 day amboseli and masai mara safari | kenya-7364-6-day-amboseli-and-masai-mara-safari.json |
| 3 | Kenya | sopa-lodges-circuit-amboseli-to-mara | 7460 | done 2026-10-16 | 7 day kenya sopa lodges safari | kenya-7460-7-day-kenya-sopa-lodges-safari.json |
| 4 | Kenya | 9-day-hells-gate-to-masai-mara-safari | 7371 | done 2026-10-16 | 9 day hells gate to masai mara safari | kenya-7371-9-day-hells-gate-to-masai-mara-safari.json |
| 5 | Kenya | amboseli-nakuru-masai-mara-safari | 7356 | done 2026-10-17 | 10 day amboseli nakuru and masai mara safari | kenya-7356-10-day-amboseli-nakuru-and-masai-mara-safari.json |
| 6 | Kenya | 4-day-naivasha-nakuru-masai-mara-safari | 6824 | done 2026-10-17 | 4 day naivasha nakuru and masai mara safari | kenya-6824-4-day-naivasha-nakuru-and-masai-mara-safari.json |
| 7 | Kenya | 7-day-best-of-solio-game-reserve-naivasha-and-masai-mara-triangle | 7467 | done 2026-10-18 | 7 day solio naivasha and masai mara safari | kenya-7467-7-day-solio-naivasha-and-masai-mara-safari.json |
| 8 | Kenya | 12-days-magical-kenya-tanzania-safari | 7380 | done 2026-10-18 | 12 day kenya and tanzania safari | kenya-7380-12-day-kenya-and-tanzania-safari.json |
| 9 | Kenya | nairobi-national-park-half-day-safari | 10970 | done 2026-10-19 | nairobi national park half day safari | kenya-10970-nairobi-national-park-half-day-safari.json |
| 10 | Kenya | 7-day-ol-pejeta-meru-and-samburu-safari | 7473 | done 2026-10-19 | 7 day ol pejeta meru and samburu safari | kenya-7473-7-day-ol-pejeta-meru-and-samburu-safari.json |
| 11 | Kenya | 7-days-masai-mara-kenya-safari | 2762 | done 2026-10-20 | 7 day amboseli nakuru and masai mara safari | kenya-2762-7-day-amboseli-nakuru-and-masai-mara-safari.json |
| 12 | Kenya | 11-day-kenya-special-five-and-big-five | 8130 | done 2026-10-20 | 11 day kenya special five and big five safari | kenya-8130-11-day-kenya-special-five-and-big-five-safari.json |
| 13 | Kenya | 5-day-black-leopard-rhino-photo-safari | 8112 | done 2026-10-21 | 5 day black leopard and rhino photography safari | kenya-8112-5-day-black-leopard-and-rhino-photography-safari.json |
| 14 | Kenya | 5-day-best-of-kenya-birding-safari | 7883 | done 2026-10-21 | 5 day kenya birding safari | kenya-7883-5-day-kenya-birding-safari.json |
| 15 | Kenya | 2-days-tsavo-west-national-park-from-diani | 7739 | done 2026-10-23 | 2 day tsavo west safari from diani | kenya-7739-2-day-tsavo-west-safari-from-diani.json |
| 17 | Kenya | mout-kemya | 2957 | done 2026-10-23 | 5 day mount kenya lakes trek | kenya-2957-5-day-mount-kenya-lakes-trek.json |
| 18 | Kenya | 4-days-mt-kenya-sirimon-chogoria-route | 6804 | done 2026-10-26 | 4 day mount kenya sirimon chogoria trek | kenya-6804-4-day-mount-kenya-sirimon-chogoria-trek.json |
| 19 | Kenya | 5-days-mt-kenya-sirimon-to-chogoria-traverse | 6806 | done 2026-10-26 | 5 day mount kenya sirimon chogoria traverse | kenya-6806-5-day-mount-kenya-sirimon-chogoria-traverse.json |
| 20 | Kenya | full-day-corporate-team-building-at-burudani-adventure-park | 6861 | done 2026-10-27 | full day corporate team building at burudani adventure park | kenya-6861-full-day-corporate-team-building-at-burudani-adventure-park.json |
| 16 | Tanzania | 4-day-wildebeest-migration-safari | 9518 | done 2026-10-27 | 4 day serengeti wildebeest migration safari | tanzania-9518-4-day-serengeti-wildebeest-migration-safari.json |
| 21 | Tanzania | 8-day-great-migration-northern-tanzania | 9539 | done 2026-10-28 | 8 day northern tanzania great migration safari | tanzania-9539-8-day-northern-tanzania-great-migration-safari.json |
| 22 | Tanzania | tarangire-ngorongoro-serengeti-safari | 9481 | done 2026-10-28 | 8 day tarangire ngorongoro and serengeti safari | tanzania-9481-8-day-tarangire-ngorongoro-and-serengeti-safari.json |
| 23 | Tanzania | northern-tanzania-safari | 9482 | done 2026-10-29 | 6 day tarangire serengeti and ngorongoro safari | tanzania-9482-6-day-tarangire-serengeti-and-ngorongoro-safari.json |
| 24 | Tanzania | 5-days-northern-tanzania-safari | 9493 | done 2026-10-29 | 5 day lake manyara ngorongoro and serengeti safari | tanzania-9493-5-day-lake-manyara-ngorongoro-and-serengeti-safari.json |
| 25 | Tanzania | 8-day-tanzania-safari-premium-parks |  | todo |  |  |
| 26 | Tanzania | 8-day-midrange-tanzania-safari |  | todo |  |  |
| 27 | Tanzania | tanzania-luxury-safari |  | todo |  |  |
| 28 | Tanzania | tanzania-honeymoon-itinerary |  | todo |  |  |
| 29 | Tanzania | 8-days-tanzania-zanzibar-classic-safari-beach |  | todo |  |  |
| 30 | Tanzania | tanzania-and-zanzibar-safari |  | todo |  |  |
| 31 | Tanzania | tanzania-safari-zanzibar-beach-holiday |  | todo |  |  |
| 32 | Tanzania | tanzania-safari-zanzibar-beach-safari |  | todo |  |  |
| 33 | Uganda | gorilla-trekking-safari-to-bwindi |  | todo |  |  |
| 34 | Uganda | gorilla-and-chimpanzee-trekking-safari |  | todo |  |  |
| 35 | Uganda | chimpanzee-trekking-and-wildlife-safari |  | todo |  |  |
| 36 | Uganda | 4-day-queen-elizabeth-national-park |  | todo |  |  |
| 37 | Uganda | 7-days-primate-and-wildlife-safari |  | todo |  |  |
| 38 | Uganda | 10-days-primate-and-wildlife-safari |  | todo |  |  |
| 39 | Uganda | 3-days-at-kidepo-valley-national-park |  | todo |  |  |
| 40 | Botswana | botswana-safari-chobe-khwai-okavango |  | todo |  |  |
| 41 | Botswana | 3-day-okavango-delta-mokoro-tour |  | todo |  |  |
| 42 | Botswana | 5-days-okavango-exploration |  | todo |  |  |
| 43 | Botswana | 7-day-moremi-khwai-and-savuti |  | todo |  |  |
| 44 | Botswana | 8-days-moremi-and-northern-exploration |  | todo |  |  |
| 45 | Botswana | 7-days-kalahari-expeditions |  | todo |  |  |
| 46 | Botswana | 5-day-boteti-tented-safari-logde |  | todo |  |  |
| 47 | Namibia | 7-day-classic-etosha-national-park |  | todo |  |  |
| 48 | Namibia | 5-day-sossusvlei-and-etosha-tour |  | todo |  |  |
| 49 | Namibia | namib-desert-and-sossusvlei-experience |  | todo |  |  |
| 50 | Namibia | 4-day-namibian-desert-dash-tour |  | todo |  |  |
| 51 | Namibia | 4-days-magical-namibia-safari |  | todo |  |  |
| 52 | Namibia | 5-days-namibia-adventure |  | todo |  |  |
| 53 | Namibia | 6-day-namibian-culture-and-scenery-tour |  | todo |  |  |
| 54 | Namibia | namibian-southern-skeleton-coast-tour |  | todo |  |  |

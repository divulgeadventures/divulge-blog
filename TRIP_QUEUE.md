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
| 25 | Tanzania | 8-day-tanzania-safari-premium-parks | 8068 | done 2026-10-31 | 8 day tarangire manyara serengeti and ngorongoro safari | tanzania-8068-8-day-tarangire-manyara-serengeti-and-ngorongoro-safari.json |
| 26 | Tanzania | 8-day-midrange-tanzania-safari | 9532 | done 2026-10-31 | 8 day midrange tanzania safari | tanzania-9532-8-day-midrange-tanzania-safari.json |
| 27 | Tanzania | tanzania-luxury-safari | 9538 | done 2026-11-02 | 8 day serengeti ngorongoro and lake eyasi safari | tanzania-9538-8-day-serengeti-ngorongoro-and-lake-eyasi-safari.json |
| 28 | Tanzania | tanzania-honeymoon-itinerary | 9499 | done 2026-11-02 | 9 day tanzania safari and zanzibar honeymoon | tanzania-9499-9-day-tanzania-safari-and-zanzibar-honeymoon.json |
| 29 | Tanzania | 8-days-tanzania-zanzibar-classic-safari-beach | 6840 | done 2026-11-04 | 8 day serengeti and zanzibar safari | tanzania-6840-8-day-serengeti-and-zanzibar-safari.json |
| 30 | Tanzania | tanzania-and-zanzibar-safari | 9511 | done 2026-11-04 | 8 day family tanzania and zanzibar safari | tanzania-9511-8-day-family-tanzania-and-zanzibar-safari.json |
| 31 | Tanzania | tanzania-safari-zanzibar-beach-holiday | 9504 | done 2026-11-05 | 11 day tanzania safari and zanzibar beach holiday | tanzania-9504-11-day-tanzania-safari-and-zanzibar-beach-holiday.json |
| 32 | Tanzania | tanzania-safari-zanzibar-beach-safari | 9527 | done 2026-11-05 | 10 day tanzania safari and zanzibar beach | tanzania-9527-10-day-tanzania-safari-and-zanzibar-beach.json |
| 33 | Uganda | gorilla-trekking-safari-to-bwindi | 8708 | done 2026-11-06 | 4 day bwindi gorilla trekking safari | uganda-8708-4-day-bwindi-gorilla-trekking-safari.json |
| 34 | Uganda | gorilla-and-chimpanzee-trekking-safari | 8738 | done 2026-11-06 | 9 day gorilla and chimpanzee trekking safari | uganda-8738-9-day-gorilla-and-chimpanzee-trekking-safari.json |
| 35 | Uganda | chimpanzee-trekking-and-wildlife-safari | 8771 | done 2026-11-07 | 6 day chimpanzee trekking and murchison falls safari | uganda-8771-6-day-chimpanzee-trekking-and-murchison-falls-safari.json |
| 36 | Uganda | 4-day-queen-elizabeth-national-park | 8696 | done 2026-11-07 | 4 day queen elizabeth national park safari | uganda-8696-4-day-queen-elizabeth-national-park-safari.json |
| 37 | Uganda | 7-days-primate-and-wildlife-safari | 8737 | done 2026-11-10 | 7 day uganda gorilla and chimpanzee safari | uganda-8737-7-day-uganda-gorilla-and-chimpanzee-safari.json |
| 38 | Uganda | 10-days-primate-and-wildlife-safari | 8770 | done 2026-11-10 | 10 day uganda primate and wildlife safari | uganda-8770-10-day-uganda-primate-and-wildlife-safari.json |
| 39 | Uganda | 3-days-at-kidepo-valley-national-park | 8716 | done 2026-11-11 | 4 day kidepo valley national park safari | uganda-8716-4-day-kidepo-valley-national-park-safari.json |
| 40 | Botswana | botswana-safari-chobe-khwai-okavango | 9843 | done 2026-11-11 | 7 day chobe moremi and khwai safari | botswana-9843-7-day-chobe-moremi-and-khwai-safari.json |
| 41 | Botswana | 3-day-okavango-delta-mokoro-tour | 9793 | done 2026-11-12 | 3 day okavango delta mokoro safari | botswana-9793-3-day-okavango-delta-mokoro-safari.json |
| 42 | Botswana | 5-days-okavango-exploration | 9769 | done 2026-11-12 | 5 day okavango moremi and khwai safari | botswana-9769-5-day-okavango-moremi-and-khwai-safari.json |
| 43 | Botswana | 7-day-moremi-khwai-and-savuti | 9844 | done 2026-11-12 | 7 day moremi khwai and savuti safari | botswana-9844-7-day-moremi-khwai-and-savuti-safari.json |
| 44 | Botswana | 8-days-moremi-and-northern-exploration | 9771 | done 2026-11-12 | 8 day moremi and khwai camping safari | botswana-9771-8-day-moremi-and-khwai-camping-safari.json |
| 45 | Botswana | 7-days-kalahari-expeditions | 9772 | done 2026-11-12 | 7 day central kalahari and nxai pan safari | botswana-9772-7-day-central-kalahari-and-nxai-pan-safari.json |
| 46 | Botswana | 5-day-boteti-tented-safari-logde | 9846 | done 2026-11-12 | 5 day boteti and okavango delta safari | botswana-9846-5-day-boteti-and-okavango-delta-safari.json |
| 47 | Namibia | 7-day-classic-etosha-national-park | 9703 | done 2026-11-13 | 7 day etosha national park safari | namibia-9703-7-day-etosha-national-park-safari.json |
| 48 | Namibia | 5-day-sossusvlei-and-etosha-tour | 9704 | done 2026-11-13 | 5 day sossusvlei and etosha tour | namibia-9704-5-day-sossusvlei-and-etosha-tour.json |
| 49 | Namibia | namib-desert-and-sossusvlei-experience | 9712 | done 2026-11-13 | 3 day sossusvlei tour from windhoek | namibia-9712-3-day-sossusvlei-tour-from-windhoek.json |
| 50 | Namibia | 4-day-namibian-desert-dash-tour | 9655 | done 2026-11-13 | 4 day sossusvlei and swakopmund tour | namibia-9655-4-day-sossusvlei-and-swakopmund-tour.json |
| 51 | Namibia | 4-days-magical-namibia-safari |  | todo |  |  |
| 52 | Namibia | 5-days-namibia-adventure |  | todo |  |  |
| 53 | Namibia | 6-day-namibian-culture-and-scenery-tour |  | todo |  |  |
| 54 | Namibia | namibian-southern-skeleton-coast-tour |  | todo |  |  |

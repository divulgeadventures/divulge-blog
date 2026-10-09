# Divulge Diaries Blog Post Template (Divulge Adventures)

Divulge Adventures Ltd is a Nairobi-based safari tour agency running tailor-made safaris in Kenya, Tanzania, Uganda, Rwanda, Namibia and Botswana, plus mountain climbs (Kilimanjaro, Mount Kenya), short excursions from Nairobi and corporate team building. Readers are mostly international travellers (UK, US, Europe, Asia) planning a first or repeat safari, plus Nairobi residents and companies for day trips and team building. The goal of every post: rank on Google and be quoted by AI answer engines, then turn readers into WhatsApp or proposal-form enquiries.

## SEO FIELDS (go into the JSON fields, never into the body)

Focus Keyword: [the main search phrase, e.g. "kenya safari cost"]

SEO Title (under 60 characters, keyword at the start, a number, a power word, positive sentiment):
[Keyword]: [Number] [Power Word] Tips/Things for [Outcome]

Permalink: [keyword-in-lowercase-hyphens], short, no year

Meta Description (under 160 characters, includes the keyword):
[What the post answers + benefit]. [East Africa context]. [Soft nudge].

Category: exactly one of: Destination Tips, Safari Themes, Great Migration & River Crossing, Accommodation Guide, Safari Planning Tips, Team Building

Tags: 3-5 relevant tags

Excerpt: 1-2 sentences, includes the keyword

FAQ: the same 4-5 questions and answers used in the FAQ section, as plain text (no HTML), for FAQ schema.

Image alt text:
1. Featured image: [Keyword] – [scene description]
2. In-content image: [scene description] (include the keyword)


## POST BODY STRUCTURE (HTML, in this order)

1. Intro: 2 short paragraphs. Paragraph 1 answers the question behind the keyword in 40-60 words, keyword in the first sentence. Paragraph 2 names the reader's problem and what the post gives them.
2. In-content image with keyword alt text.
3. Info Box 1: Quick Facts (5-7 labelled bullets).
4. H2 sections: 5-7 main sections, H3s for sub-points. Keyword in at least one H2 and one H3. Include at least one table.
5. Trip CTA (green): right after the section where the reader is most ready to book (itinerary, cost or "how to plan" section).
6. Info Box 2: Insider Tips / Checklist.
7. Plan My Safari CTA (orange): after the experience or planning content.
8. H2: Frequently Asked Questions, 4-5 Q&As as H3s.
9. H2: Final Thoughts: 2-3 sentence summary of the answer, then a nudge to act.
10. WhatsApp CTA at the very end.


## RULES FOR A 90+ RANK MATH SCORE

- Length: at least 1,600 words; aim for 1,800-2,300. Pillar posts 2,500+.
- Keyword density: 1-1.2%, spread naturally (intro, an H2, an H3, body, Final Thoughts).
- Internal links: at least 3 internal links: 1+ to a money page (destination, activity, trip or mountain climbing page on divulgeadventures.com), 2+ to existing Divulge Diaries posts listed in CONTENT_MAP.md (never the post itself).
- External link: at least 1 dofollow link to an authority source (official park, tourism board, government, WHO/CDC/NHS travel health, IATA, UNESCO) with target="_blank" rel="noopener". Never nofollow. Never link to other tour operators or booking sites (no Safaribookings, TripAdvisor tour listings, Viator, GetYourGuide, competitor agencies).
- Paragraphs under 120 words, ideally 2-4 sentences.
- No bold inside paragraphs. Bold labels in lists and boxes are fine.
- At least one image inside the body with the keyword in its alt text.
- Every word earns its place: reach length through useful coverage (comparisons, month-by-month detail, costs broken down, follow-up questions), never padding.


## RULES FOR AEO (Google AI Overviews, ChatGPT, Perplexity, Gemini)

- Answer first: intro paragraph 1 answers the keyword's question in 40-60 words.
- Question headings: at least 3 H2/H3s (outside the FAQ) phrased as real searches (What / When / How much / Is / Which / Can / How many days).
- Under every H2, the first paragraph answers the heading directly in under 60 words before the detail.
- Self-contained passages: open sections by naming the subject ("The Masai Mara..." not "It...").
- Specific, verified facts: distances (km), drive and flight times, altitudes, best months, park sizes, permit rules, fee ranges with the source and year checked.
- Quick Facts box: 5-7 bullets, each with a bold label (e.g. <strong>Best months:</strong> July-October).
- Tables: at least one table (cost breakdown, month-by-month, park comparison, itinerary days, lodge tiers).
- FAQ answers: 40-80 words each, start with a direct answer and repeat the subject.
- Trust: write as a Nairobi-based East African safari specialist. Never invent personal stories, client quotes, reviews, statistics, awards or numbers of years in business.
- No year in the title or slug. Mention the year only next to a fact that needs dating (e.g. "fees checked October 2026").


## THE BOXES (exact HTML; change only the bracketed text)

Table style (copy exactly; every <th> carries its own light background and dark text so the theme cannot make header text unreadable; never put a dark background on a table header or row). Every table has class="dvg-table" and every <td> has data-label set to its column's header text (exact same words), because on phones each row becomes a card that shows those labels. Keep tables to 2-4 columns and put the item name in the first column:
<table class="dvg-table" style="width:100%; border-collapse:collapse; margin:20px 0;"><thead><tr><th style="padding:12px 10px; text-align:left; background-color:#f6efe7; color:#043D0E; font-weight:bold; border-bottom:3px solid #C97A3D;">[Header 1]</th><th style="padding:12px 10px; text-align:left; background-color:#f6efe7; color:#043D0E; font-weight:bold; border-bottom:3px solid #C97A3D;">[Header 2]</th></tr></thead><tbody><tr style="border-bottom:1px solid #e5ddd3;"><td style="padding:10px; color:#1b1b1b;" data-label="[Header 1]">[Cell]</td><td style="padding:10px; color:#1b1b1b;" data-label="[Header 2]">[Cell]</td></tr></tbody></table>

### Info Box (used twice; change the heading: Quick Facts / Insider Tips / Packing Checklist / Key Takeaways)

<div style="background-color:#f6efe7; border-left:5px solid #C97A3D; padding:18px 20px; margin:20px 0; border-radius:6px;">
  <h3 style="margin-top:0; color:#043D0E;">[Quick Facts]</h3>
  <ul style="margin:0; padding-left:20px; line-height:1.7;">
    <li><strong>[Label]:</strong> [Fact]</li>
    <li><strong>[Label]:</strong> [Fact]</li>
  </ul>
</div>

### Trip CTA (green). Point the button to the most relevant money page for the post (see CONTENT_MAP.md "Money pages"):

<div style="background-color:#eef4ef; border:2px solid #043D0E; border-radius:10px; padding:22px; text-align:center; margin:25px 0;">
  <p style="margin:0 0 14px 0; font-size:17px; font-weight:bold; color:#043D0E;">[Short headline tied to the post, e.g. "See our Masai Mara safari packages"]</p>
  <p style="margin:0 0 16px 0; font-size:14px; color:#333333; line-height:1.6;">[1-2 sentences: what the reader gets on that page, e.g. tailor-made itineraries, private vehicles, expert local guides]</p>
  <a href="[MONEY PAGE URL]" target="_blank" style="display:inline-block; background-color:#043D0E; color:#ffffff; padding:12px 28px; border-radius:30px; text-decoration:none; font-weight:bold; font-size:15px;">[Button text, e.g. View Kenya Safaris]</a>
</div>

### Plan My Safari CTA (orange):

<div style="background-color:#f6efe7; border:2px solid #C97A3D; border-radius:10px; padding:22px; text-align:center; margin:25px 0;">
  <p style="margin:0 0 14px 0; font-size:17px; font-weight:bold; color:#1b1b1b;">Let us plan your safari</p>
  <p style="margin:0 0 16px 0; font-size:14px; color:#333333; line-height:1.6;">Tell us your dates, budget and dream sightings, and our Nairobi team will send you a free tailor-made itinerary and quote.</p>
  <a href="https://divulgeadventures.com/safari-proposal-request/" target="_blank" style="display:inline-block; background-color:#C97A3D; color:#ffffff; padding:12px 28px; border-radius:30px; text-decoration:none; font-weight:bold; font-size:15px;">Get My Free Safari Quote</a>
</div>

### WhatsApp CTA (very end). Replace [POST_TOPIC] with the topic URL-encoded (spaces as %20):

<div style="background-color:#eef4ef; border:2px solid #043D0E; border-radius:10px; padding:22px; text-align:center; margin:25px 0;">
  <p style="margin:0 0 14px 0; font-size:17px; font-weight:bold; color:#1b1b1b;">[Question hook tied to the post, e.g. "Not sure which month suits your migration safari?"]</p>
  <p style="margin:0 0 16px 0; font-size:14px; color:#333333; line-height:1.6;">Chat with a Divulge Adventures safari consultant on WhatsApp for honest advice, a quick answer and a quote built around you.</p>
  <a href="https://wa.me/254706223888?text=Hi%20Divulge%20Adventures%2C%20I%20just%20read%20your%20post%20on%20[POST_TOPIC]" target="_blank" style="display:inline-block; background-color:#25D366; color:#ffffff; padding:12px 28px; border-radius:30px; text-decoration:none; font-weight:bold; font-size:15px;">Chat on WhatsApp</a>
</div>

### In-content image:

<img src="[IMAGE URL]" alt="[scene description with keyword]" style="width:100%; height:auto; border-radius:8px; margin:20px 0;" />

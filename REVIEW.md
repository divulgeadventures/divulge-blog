# Critic pass (required before every commit of a new post or refresh)

Added 24 Nov 2026 so the site can publish more without looking mass-produced.
validate.py (quality_gates.py) rejects a post without the "review" block below.

## How to do it
After the post passes every other validate.py check, stop writing and re-read the
whole post as a strict travel editor who did not write it. Compare it with the top 3
Google results you saw during research for the same keyword.

Score each line 0-2, then add them up (max 10):

| # | Question | 0 | 1 | 2 |
|---|----------|---|---|---|
| 1 | Usefulness: does it answer the searcher's real question faster and more completely than the top 3? | worse | equal | clearly better |
| 2 | Accuracy: is every fee, permit, visa, health, flight and distance figure linked to an official or recent source with "(checked Month YYYY)"? | gaps | mostly | all |
| 3 | Originality: does it contain something the top 3 don't (local detail, field note, honest trade-off, month-by-month specifics, a real Divulge itinerary)? | nothing | a little | clearly |
| 4 | Reader fit: is it written for the stated reader (country, budget, group type) with no padding or generic filler paragraphs? | generic | partly | fully |
| 5 | Human voice: does it read like a Nairobi safari specialist wrote it (varied sentences, no template phrases, no repeated promo lines)? | template-like | mixed | natural |

## Then
- Fix every weak point you found and re-score once.
- Score 8-10: publish as normal.
- New post still below 8: set "status": "draft" (the owner reviews it in WordPress) and say why in the run log.
- Refresh still below 8: do not commit it; log why and leave the queue row as `todo`.

## JSON field (add to the post file)
```json
"review": {
  "score": 9,
  "vs_top3": "One sentence: what this post gives readers that the top 3 results don't.",
  "fixes": "One sentence: what you changed after the first read-through."
}
```
Also add `"field_notes_used": ["<date> <first words of the note>"]` when you used a note from FIELD_NOTES.md (otherwise leave it out or use []).

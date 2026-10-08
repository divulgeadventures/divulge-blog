# Post JSON format

```json
{
  "title": "Post title (H1)",
  "slug": "url-slug",
  "category": "Destination Tips | Safari Themes | Great Migration & River Crossing | Accommodation Guide | Safari Planning Tips | Team Building",
  "tags": ["tag one", "tag two"],
  "focus_keyword": "main keyword",
  "meta_title": "SEO title, under 60 chars",
  "meta_description": "SEO description, under 160 chars",
  "excerpt": "1-2 sentence summary",
  "content": "<p>Full post HTML (no H1)...</p>",
  "faq": [{"q": "Question?", "a": "Plain-text answer, same as in the post."}],
  "featured_image": "https://direct-image-url.jpg",
  "featured_image_alt": "alt text containing the focus keyword",
  "status": "publish | draft",
  "publish_at": "2026-10-08T10:00:00+03:00"
}
```

- `status: "draft"` lands the post as a draft for review. A future `publish_at` schedules it.
- `faq` is printed as FAQPage schema on the post by the sync snippet.
- Refresh an existing post: add `"update_slug": "<existing-slug>"` and set `slug` to the same value. URL, date and author stay; title, content, excerpt, category, tags, Rank Math fields, FAQ and (optionally) featured image are replaced. WordPress keeps the old version under Revisions.
- Filenames: new posts `posts/YYYY-MM-DD-<slot 1-3>-<slug>.json`; refreshes `posts/refresh-YYYY-MM-DD-<slug>.json` (refresh files must not start with the date, so they never count as one of the day's 3 posts).

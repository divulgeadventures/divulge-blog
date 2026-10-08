# divulge-blog

Automated Divulge Diaries posts for [divulgeadventures.com](https://divulgeadventures.com).

- `posts/` — one JSON file per post. The site's "Divulge Blog Sync" WPCode snippet (`wpcode/divulge-blog-sync.php`) checks this folder hourly and publishes any new file.
- `TEMPLATE.md` — structure, SEO and AEO rules every post follows.
- `TOPIC_BANK.csv` — about 1,900 prioritised topics (300+ per destination) that new posts are picked from.
- `CONTENT_MAP.md` — how topics are picked, money pages, rules and the list of existing posts.
- `TOPIC_IDEAS.md` — add your own topic ideas here; they are written first.
- `REFRESH_QUEUE.md` — older posts rewritten in place, one a day.
- `topics-log.md` — every topic already written.
- `POST_FORMAT.md` — the JSON fields the site understands.

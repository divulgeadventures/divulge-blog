# Trip packages (WP Travel Engine)

Each `.json` file here updates one or more existing **trip** posts on divulgeadventures.com.
The Trip Optimiser snippet (`wpcode/divulge-trip-optimiser.php`, Tools → Trip Optimiser) checks this
folder every 5 minutes, imports new or changed files, adds the photos, and publishes trips marked
`"status": "publish"`.

Blog posts live in `posts/` and are handled by a different snippet. Nothing in this folder touches blog posts.

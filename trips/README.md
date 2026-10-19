# Trip packages (WP Travel Engine)

Each `.json` file here updates one or more existing **trip** posts on divulgeadventures.com.
The Trip Optimiser snippet (`wpcode/divulge-trip-optimiser.php`, Tools → Trip Optimiser) checks this
folder every 5 minutes, imports new or changed files, adds the photos, and publishes trips marked
`"status": "publish"`.

Blog posts live in `posts/` and are handled by a different snippet. Nothing in this folder touches blog posts.

## New trips (no ID yet)

A file can also create a brand-new trip. Leave out `"id"` and give a unique `"key"` plus a `"title"`.
The optimiser creates the trip once as a draft, remembers the key, and updates the same trip on later imports.

Extra fields for any trip:

- `"duration"`: number of days (nights = days - 1 unless `"nights"` is given)
- `"itinerary"`: `[ { "label": "Day 1", "title": "...", "content": "<p>...</p>" } ]`
- `"includes"` / `"excludes"`: lists of short lines for the Cost tab
- `"terms"`: categories, e.g. `{ "activities": ["birding-safaris"], "destination": ["Kenya"], "trip_tag": ["Photography safari"] }`.
  Activities, destinations and trip types must already exist (matched by slug or name). Tags are created if new.

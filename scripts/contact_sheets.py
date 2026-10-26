#!/usr/bin/env python3
"""Make numbered contact sheets of Media Library photos (review/sheet-XX.jpg) for photo matching."""
import io, json, urllib.request
from PIL import Image, ImageDraw
UA = "Mozilla/5.0 (compatible; DivulgeReview/1.0)"
items = json.load(open("review/candidates.json"))
W, H, COLS, PER = 320, 213, 5, 20
for s in range(0, len(items), PER):
    batch = items[s:s + PER]
    sheet = Image.new("RGB", (COLS * W, ((len(batch) + COLS - 1) // COLS) * (H + 22)), "white")
    d = ImageDraw.Draw(sheet)
    for i, it in enumerate(batch):
        x, y = (i % COLS) * W, (i // COLS) * (H + 22)
        try:
            req = urllib.request.Request(it["url"], headers={"User-Agent": UA})
            im = Image.open(io.BytesIO(urllib.request.urlopen(req, timeout=40).read())).convert("RGB")
            im.thumbnail((W - 6, H - 6))
            sheet.paste(im, (x + 3, y + 3))
        except Exception as e:
            d.text((x + 10, y + 90), "ERROR " + str(e)[:40], fill="red")
        d.rectangle([x, y + H, x + W, y + H + 22], fill="black")
        d.text((x + 6, y + H + 5), f"#{it['n']}", fill="yellow")
    sheet.save(f"review/sheet-{s // PER + 1:02d}.jpg", quality=80)
    print("sheet", s // PER + 1)

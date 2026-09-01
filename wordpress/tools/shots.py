"""
Capture viewport-sized screenshots so the build can be looked at rather than
assumed. Never full-page: a long page produces an image too tall to review.

Usage: python3 tools/shots.py http://127.0.0.1:8734
"""
import sys
from pathlib import Path
from playwright.sync_api import sync_playwright

BASE = sys.argv[1] if len(sys.argv) > 1 else "http://127.0.0.1:8734"
OUT = Path(__file__).resolve().parent.parent / "shots"
OUT.mkdir(exist_ok=True)

# route, label, how many viewport-heights down to capture
PLAN = [
    ("/", "home", [0, 1, 2, 3, 4, 5, 6]),
    ("/managed-it-services/", "service", [0, 1, 2]),
    ("/industries/", "industries", [0, 1]),
    ("/contact/", "contact", [0, 1]),
    ("/support-center/", "support", [0]),
    ("/resources/", "resources", [0, 1]),
    ("/why-trg/", "whytrg", [0, 1]),
]


def settle(page):
    page.evaluate("Array.from(document.images).forEach(i => { i.loading = 'eager'; })")
    try:
        page.wait_for_function(
            "Array.from(document.images).every(i => i.complete && i.naturalWidth > 0)",
            timeout=15000,
        )
    except Exception:
        pass


with sync_playwright() as p:
    browser = p.chromium.launch()

    desktop = browser.new_context(viewport={"width": 1280, "height": 720})
    page = desktop.new_page()
    for route, label, screens in PLAN:
        page.goto(BASE + route, wait_until="networkidle")
        settle(page)
        for i in screens:
            page.evaluate(f"window.scrollTo(0, {i} * window.innerHeight)")
            page.wait_for_timeout(500)
            page.screenshot(path=str(OUT / f"desktop-{label}-{i}.png"))
            print("desktop", label, i)
    desktop.close()

    mobile = browser.new_context(
        viewport={"width": 390, "height": 780},
        device_scale_factor=1,
        is_mobile=True,
        has_touch=True,
    )
    mpage = mobile.new_page()
    for route, label, screens in [("/", "home", [0, 1, 2]), ("/contact/", "contact", [0, 1])]:
        mpage.goto(BASE + route, wait_until="networkidle")
        settle(mpage)
        for i in screens:
            mpage.evaluate(f"window.scrollTo(0, {i} * window.innerHeight)")
            mpage.wait_for_timeout(500)
            mpage.screenshot(path=str(OUT / f"mobile-{label}-{i}.png"))
            print("mobile", label, i)

    # The mobile drawer, opened.
    mpage.goto(BASE + "/", wait_until="networkidle")
    mpage.click("[data-trg-drawer-open]")
    mpage.wait_for_timeout(400)
    mpage.screenshot(path=str(OUT / "mobile-drawer.png"))
    print("mobile drawer")
    mobile.close()

    browser.close()

print(f"\nScreenshots in {OUT}")

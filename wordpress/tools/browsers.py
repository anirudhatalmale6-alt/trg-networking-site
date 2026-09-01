"""
Cross-engine check.

The change request asks for Chrome, Edge, Firefox, iPhone and Android. Those
five are three rendering engines: Chrome, Edge and Android Chrome are Blink,
Firefox is Gecko, and iPhone Safari is WebKit. This loads the key pages in all
three, at a desktop and a phone width, and checks the things that actually
differ between engines: a script that throws, a layout that scrolls sideways,
and a heading that fails to render.

Usage: python3 tools/browsers.py http://127.0.0.1:8736
"""
import sys
from playwright.sync_api import sync_playwright

BASE = (sys.argv[1] if len(sys.argv) > 1 else "http://127.0.0.1:8736").rstrip("/")

ROUTES = ["/", "/services/", "/managed-it-services/", "/cmmc-readiness/",
          "/industries/", "/contact/", "/about/", "/support-center/"]

VIEWPORTS = [("desktop", 1280, 720), ("phone", 390, 780)]

problems = []


def run(browser, label):
    for name, w, h in VIEWPORTS:
        ctx = browser.new_context(viewport={"width": w, "height": h})
        page = ctx.new_page()
        errors = []
        page.on("pageerror", lambda e: errors.append(str(e)))

        for route in ROUTES:
            del errors[:]
            resp = page.goto(BASE + route, wait_until="load")
            if resp is None or resp.status != 200:
                problems.append(f"{label}/{name} {route}: HTTP "
                                f"{resp.status if resp else 'none'}")
                continue

            h1s = page.locator("h1").count()
            if h1s != 1:
                problems.append(f"{label}/{name} {route}: {h1s} h1 elements")

            # Sideways scrolling: the single most common cross-engine break.
            overflow = page.evaluate(
                "() => document.documentElement.scrollWidth - "
                "document.documentElement.clientWidth")
            if overflow > 1:
                problems.append(
                    f"{label}/{name} {route}: {overflow}px of horizontal overflow")

            if errors:
                problems.append(f"{label}/{name} {route}: JS error {errors[0]}")

        ctx.close()
        print(f"  {label:9} {name:8} {len(ROUTES)} pages ok"
              if not problems else f"  {label:9} {name:8} see below")


with sync_playwright() as p:
    for engine, label in ((p.chromium, "Blink"), (p.firefox, "Gecko"),
                          (p.webkit, "WebKit")):
        browser = engine.launch()
        run(browser, label)
        browser.close()

print()
if problems:
    print(f"{len(problems)} problem(s):")
    for line in problems:
        print(" -", line)
    sys.exit(1)
print("Blink (Chrome, Edge, Android), Gecko (Firefox) and WebKit (Safari, "
      "iPhone): no errors, no overflow, one heading per page.")

"""
Walk every page of the WordPress build in a real browser and check the things
that quietly break a site: a missing or duplicated main heading, an image that
404s, a link that goes nowhere, sideways scrolling on a phone, or a JavaScript
error. Run it before every delivery.

Usage: python3 tools/qa.py http://127.0.0.1:8734
"""
import sys
from playwright.sync_api import sync_playwright

BASE = sys.argv[1] if len(sys.argv) > 1 else "http://127.0.0.1:8734"

ROUTES = [
    "/", "/services/", "/industries/", "/why-trg/", "/about/", "/contact/",
    "/resources/", "/resources/case-studies/", "/resources/guides/",
    "/managed-it-services/", "/help-desk-it-support/", "/cybersecurity/",
    "/microsoft-365-cloud/", "/azure/", "/secure-ai-adoption/",
    "/cmmc-readiness/", "/backup-business-continuity/",
    "/network-infrastructure/", "/strategic-it-vcio/",
    "/construction/", "/manufacturing/", "/government-contractors/",
    "/professional-services/", "/support-center/",
    "/privacy/", "/terms/", "/accessibility/",
]

problems = []


def check(page, route):
    errors = []
    page.on("pageerror", lambda e: errors.append(f"JS error: {e}"))
    page.on("console", lambda m: errors.append(f"console.{m.type}: {m.text}")
            if m.type == "error" else None)

    resp = page.goto(BASE + route, wait_until="networkidle")
    if resp is None or resp.status != 200:
        problems.append(f"{route}: HTTP {resp.status if resp else 'no response'}")
        return

    # Exactly one <h1>.
    h1s = page.eval_on_selector_all("h1", "els => els.map(e => e.innerText.trim())")
    if len(h1s) != 1:
        problems.append(f"{route}: {len(h1s)} <h1> elements ({h1s})")

    title = page.title()
    if not title or len(title) < 10:
        problems.append(f"{route}: weak <title> {title!r}")

    # Scroll the whole page first. Everything below the fold is loading="lazy",
    # so checking images without this reports a perfectly good footer logo as
    # broken — the instrument would be failing, not the page.
    page.evaluate(
        "async () => {"
        "  const step = window.innerHeight;"
        "  for (let y = 0; y < document.body.scrollHeight; y += step) {"
        "    window.scrollTo(0, y);"
        "    await new Promise(r => setTimeout(r, 60));"
        "  }"
        "  window.scrollTo(0, 0);"
        "}"
    )
    # Force every lazy image to load, then wait for real pixels.
    #
    # Checking `complete` alone is a trap: an image with loading="lazy" that has
    # not been triggered yet reports complete === true and naturalWidth === 0,
    # which looks exactly like a 404. Flipping them to eager first is what makes
    # the difference between "not loaded yet" and "actually broken" real.
    page.evaluate("Array.from(document.images).forEach(i => { i.loading = 'eager'; })")
    try:
        page.wait_for_function(
            "Array.from(document.images).every(i => i.complete && i.naturalWidth > 0)",
            timeout=15000,
        )
    except Exception:
        pass

    # Images that failed to load.
    broken = page.eval_on_selector_all(
        "img",
        "els => els.filter(e => !e.complete || e.naturalWidth === 0).map(e => e.currentSrc || e.src)",
    )
    for src in broken:
        problems.append(f"{route}: broken image {src}")

    # Links that go nowhere.
    dead = page.eval_on_selector_all(
        "a[href]",
        "els => els.filter(e => { const h = e.getAttribute('href');"
        " return h === '#' || h === '' || h.startsWith('javascript:'); })"
        ".map(e => e.textContent.trim().slice(0, 40))",
    )
    for text in dead:
        problems.append(f"{route}: dead link {text!r}")

    # Empty headings and empty buttons.
    empty = page.eval_on_selector_all(
        "h1,h2,h3",
        "els => els.filter(e => !e.textContent.trim()).length",
    )
    if empty:
        problems.append(f"{route}: {empty} empty heading(s)")

    # Sideways scroll at phone widths.
    for width in (320, 390):
        page.set_viewport_size({"width": width, "height": 720})
        page.wait_for_timeout(120)
        scroll_w = page.evaluate("document.documentElement.scrollWidth")
        if scroll_w > width + 1:
            problems.append(f"{route}: overflows at {width}px (scrollWidth {scroll_w})")
    page.set_viewport_size({"width": 1280, "height": 720})

    # Words of real text — a page that renders empty is the failure mode both
    # source builds shipped.
    words = page.evaluate("document.querySelector('main').innerText.trim().split(/\\s+/).length")
    if words < 80:
        problems.append(f"{route}: only {words} words of visible text")

    for err in errors:
        problems.append(f"{route}: {err}")

    print(f"  {route:34s} h1={len(h1s)} words={words} ok")


with sync_playwright() as p:
    browser = p.chromium.launch()
    ctx = browser.new_context(viewport={"width": 1280, "height": 720})
    page = ctx.new_page()
    for route in ROUTES:
        check(page, route)
    browser.close()

print()
if problems:
    print(f"{len(problems)} PROBLEM(S):")
    for problem in problems:
        print("  -", problem)
    sys.exit(1)
print(f"All {len(ROUTES)} pages clean.")

# TRG Networking — merged site hand-off

The Hostinger design language is the base theme. Everything valuable from the
Lovable build has been transplanted into it and rebuilt so it behaves natively:
same fonts, same blue, same spacing scale, same card treatment.

---

## 1. Where to edit things

Almost everything you will want to change lives in **two files**. You do not
need to touch layout code to change copy, phone numbers, services or industries.

### `src/data/site.js` — everything that repeats across the site

| What you want to change | Edit this |
|---|---|
| Phone number, email, address, LinkedIn | `company` |
| The four homepage counters | `stats` |
| Header / footer / dropdown menus | `mainNav`, `servicesNav`, `industriesNav` |
| Homepage service cards | `services` |
| Homepage industry list | `industries` |
| Client quotes | `testimonials` |
| Hero badges ("Microsoft Partner" etc.) | `heroBadges` |
| Partner logo strip | `partners` |
| The "Service of interest" dropdown on the form | `serviceOptions` |

Change the phone number in `company` and it updates in the top bar, the mobile
menu, the footer, every call-to-action band and every service page at once.

### `src/data/detailPages.js` — the twelve service and industry pages

Each page is one object. To edit the CMMC page, find `cmmc:` and change
`title`, `lede`, `features`, `perspective` or `faq`. To add a whole new
service page:

1. Add an entry to `detailPages` (copy an existing one as a template).
2. Add the slug to `SERVICE_SLUGS` in `src/App.jsx`.
3. Add it to `servicesNav` in `src/data/site.js` so it appears in the menu.
4. Add the route to `ROUTES` in `scripts/prerender.mjs`.

All twelve pages share one template (`src/pages/DetailPage.jsx`), which is why
imported Lovable sections look purpose-built rather than bolted on — spacing and
type come from the same place as the rest of the site.

### Everything else

| Page | File |
|---|---|
| Homepage | `src/pages/Home.jsx` |
| Services, Industries, Why TRG, About | `src/pages/Simple.jsx` |
| Contact, Resources | `src/pages/Contact.jsx` |
| Case studies, Guides, Privacy, Terms, Accessibility, 404 | `src/pages/Misc.jsx` |
| Header / utility bar | `src/components/Header.jsx` |
| Footer | `src/components/Footer.jsx` |
| Colours and fonts | `tailwind.config.js` |

**To change the brand colour** (for example to the purple from the Lovable
build), edit the `brand` palette in `tailwind.config.js`. That single change
recolours every button, link, icon tile, eyebrow and gradient on the site.

---

## 2. Building and publishing

```
npm install          # once
npm run build        # produces dist/
```

Upload **the contents of `dist/`** plus the `api/` folder and `.htaccess` to
the web root. Nothing else needs to go on the server.

`npm run build` also pre-renders all 24 pages to real HTML files. Set your
domain first so the canonical URLs and sitemap are right:

```
SITE_URL=https://www.trgnetworking.com npm run build
```

If the build says *"Pre-render SKIPPED — no browser available"*, run
`npx playwright install chromium` once, then build again. The site still works
without it; crawlers just see less.

### Zero-downtime publish

Upload to a new folder next to the live one, check it, then swap. Do not delete
the old folder until the new one is confirmed working.

---

## 3. The contact form — read this one

The previous Hostinger form wrote submissions into a hidden database table
inside the Horizons project and **emailed nobody**. Visitors saw
"Message Sent — a member of our team will be in touch within 2 business hours"
while the enquiry sat somewhere no one was watching. Check that old table for
unread leads.

The form now posts to `api/contact.php`, which:

- emails the enquiry to `$NOTIFY_TO`, with **Reply-To set to the enquirer**, so
  hitting reply goes straight back to them;
- writes a CSV backup to a `trg-private/` folder **above** the web root, so a
  mail outage never loses a lead;
- rejects invalid emails, silently absorbs bots via a honeypot field, and limits
  each IP to 5 submissions per 10 minutes;
- **never reports success unless the mail actually went out.** If sending fails
  the visitor is shown their message as a pre-filled email plus the phone
  number, so the lead is not silently dropped.

**Two settings to change**, both at the top of `public/api/contact.php`:

```php
$NOTIFY_TO    = 'info@trgnetworking.com';      // who receives enquiries
$FROM_ADDRESS = 'website@trgnetworking.com';   // must be a real mailbox ON THIS DOMAIN
```

`$FROM_ADDRESS` matters. If it is an address on a domain the server is not
authorised to send for, most providers will spam-folder or reject the mail.

**This requires PHP.** If the site ends up on static-only hosting, tell me and
I will switch the form to a hosted endpoint instead — it is a one-line change
to `ENDPOINT` in `src/components/ContactForm.jsx`.

After going live, send one test enquiry and confirm it arrives.

---

## 4. What changed from the two source sites

### Brought over from Lovable
- The headline "Simpler IT. Stronger security. A team that responds."
- The top utility bar with phone and "Existing Client Support"
- The four outcome cards ("Less disruption. More confidence.")
- Rewritten, warmer service and industry copy throughout
- The "Multiple eyes on every request" section
- The numbered industry list
- The AI enablement panel ("Use AI with a plan. Not a free-for-all.")
- The three-step "Start with a conversation" process strip
- The dark "TRG perspective" bands
- The FAQ accordions on Managed IT, Cybersecurity and Secure AI
- **Named client testimonials** (see below)
- 8 pages that did not exist on Hostinger: Help Desk, four industry detail
  pages, Privacy, Terms and Accessibility
- The real LinkedIn URL, the street address and `marketing@` as a contact route

### Kept from Hostinger
- The whole design language: blue `#2563EB`, Outfit / Plus Jakarta Sans / Inter,
  card and button styling, spacing scale
- The photography
- The stats band, partner strip and hero trust badges
- **The contact form** — the only real form on either site
- 3 pages Lovable did not have: Azure Cloud, Case Studies, Guides & Downloads

### Fixed along the way
- **Broken image.** The Microsoft section pointed at a Hostinger CDN file that
  returns 404. Replaced with a real photo.
- **Four dead footer links.** The social icons all pointed at `#` on all 16
  pages. Now one working LinkedIn link — placeholders removed rather than left
  looking broken.
- **Mobile.** The Lovable hero scrolled sideways on a phone; its floating cards
  hung off the right edge with text cut mid-word. Rebuilt so nothing overflows
  at any width down to 320px.
- **Invented testimonials.** The Hostinger build carried three quotes from
  "James R.", "Dr. Patricia M." and "Michael T." — initials only, no company.
  Replaced with the two attributed quotes from the Lovable build (Nick
  Pirovolidis, BSC America; Todd Hirsch, Belt Built Contracting). Unverifiable
  testimonials are a real liability, so I did not carry them across.
- **Empty HTML for crawlers.** Both builds served `<div id="root"></div>` and
  nothing else. All 24 pages are now pre-rendered — the homepage went from
  0 words to over 1,000 in the served source. Added `sitemap.xml`, `robots.txt`,
  per-page titles, descriptions, canonicals, Open Graph tags and
  ProfessionalService structured data.
- **Old URLs.** The two builds used different slugs for the same page. All 10
  Lovable URLs 301-redirect to their merged equivalent, so nothing already
  shared or indexed breaks.
- **Images.** 10 MB of PNGs converted to WebP — now 572 KB total, same quality.
- **Accessibility.** Skip link, visible focus rings, one `<h1>` per page,
  labelled form fields, alt text, reduced-motion support. The Lovable build
  shipped an accessibility page; the site now honours it.

### Two numbers you should check
The old site claimed **"28+ Years of Experience"** and **"20×7 Monitoring"**
while also saying "since 1992". 1992 is 34 years ago, and 20×7 looks like a
typo for 24×7. I replaced the years counter with "1992 — serving clients since",
which cannot go stale, and set the other to 24×7 to match your own footer.
The "163+ organizations served" figure is carried over unchanged — confirm it
is still current.

---

## 5. Not done / needs a decision

- **Colour.** Lovable is purple, which matches your logo; Hostinger is blue.
  Your brief said keep Hostinger as the base, so the site is blue. One line in
  `tailwind.config.js` flips it.
- **Healthcare and Nonprofits** appear on the Industries page as full sections
  rather than their own pages — Lovable had no copy for them and I would rather
  not invent it. Give me a few lines each and they become detail pages.
- **Resources downloads** are marked "Coming soon" rather than given buttons
  that go nowhere. Send me the PDFs and they become real downloads.
- **Case studies** are quotes plus an honest note that write-ups are in
  progress, rather than invented client stories.

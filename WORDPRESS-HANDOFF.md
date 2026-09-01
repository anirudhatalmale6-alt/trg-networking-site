# TRG Networking — WordPress build

The merged site as a WordPress theme and plugin: the Hostinger build's design
language carrying the Lovable build's content, structure and URL structure,
with the consolidated change request of 1 September applied throughout.
Twenty-seven pages, all editable in the normal WordPress dashboard.

> **Version 1.1.** What changed from 1.0, and the five things still waiting on
> a decision from TRG, are listed in section 10 at the end of this document.

Two files:

| File | What it is |
|---|---|
| `trg-networking-theme.zip` | The theme — design, header, footer, navigation, page layouts |
| `trg-site-plugin.zip` | The plugin — section blocks, contact form, editable card lists, old-URL redirects |

Both are needed. The plugin is separate on purpose: the redirect map for the old
site's URLs and every contact-form submission must survive a theme change.

---

## 1. Installing

Three steps, all from the WordPress dashboard. No FTP required.

1. **Appearance → Themes → Add New → Upload Theme** → choose
   `trg-networking-theme.zip` → Install → **Activate**.
2. **Plugins → Add New → Upload Plugin** → choose `trg-site-plugin.zip` →
   Install → **Activate**.
3. **Tools → TRG Setup** → click **Build the site**.

Step 3 creates the twenty-seven pages, the service, industry and testimonial
cards, and the header and footer menus, and sets the homepage.

It is safe to run more than once. Anything that already exists is left exactly
as it is, so a page you have edited will never be overwritten by a second click.

### Updating to a newer version later

If I send a newer plugin, install it the same way (Plugins → Add New → Upload)
and WordPress replaces the old one. Pages that already exist keep whatever text
they currently have, which is usually what you want.

When you *do* want the revised wording applied to existing pages, there is a
second button at the bottom of **Tools → TRG Setup**: *Replace page text with
the shipped version*. It asks for confirmation first, because it discards any
edits made in the page editor. Menus, cards, company details, enquiries and
uploaded images are never touched, and WordPress keeps a revision of every page
so a single page can be rolled back from its own editor afterwards.

### Do this on a staging site first

Do **not** install this over the live www.trgnetworking.com until you have
clicked through it. Either put it on a staging copy, or install it on a
subdomain or subfolder and look at it there. The go-live sequence is in
section 6.

---

## 2. Where to edit what

### Text on a page → **Pages**

Open the page and edit it like any WordPress page. The words are in plain text.
A section like this:

```
[trg_perspective title="Every request is seen. Every solution is explained."
                 body="Every recommendation should serve the business."]
```

is one dark quote band. Change the words between the quote marks; leave the
square brackets and the attribute names alone. Full list of sections in
section 7.

Anything that is just a heading and a paragraph — the privacy, terms and
accessibility pages — is ordinary WordPress blocks, edited visually.

### Phone, address, email, social profiles → **Appearance → Customize → TRG company details**

Changing the phone number here changes it in the top bar, the footer, every
"Call us" button, the contact page and the markup search engines read — all at
once. There is nowhere else to change it, which is the point.

Leave a social field blank and that icon disappears rather than linking to
nothing.

### The service cards → **Service cards**

The eight cards on the homepage and the Services page. Each one has a title, a
short description, an icon and the page it links to. Add a service by adding a
card; reorder them with the **Order** field under Page Attributes.

Adding a *card* does not create a *page*. If the new service needs its own page,
create the page first (Pages → Add New), then point the card at it.

### The industry rows → **Industry cards**

Same idea. The small grey line under each title is the "Small print" field.

### The two quotes → **Testimonials**

Person's name goes in the title, the quote in the body, the company in the
"Company" box.

### Menus → **Appearance → Menus**

Four menus: Main menu (header), and three footer columns, plus the legal links.
The header dropdowns are just second-level items under "Services" and
"Industries".

### Colours → the theme's stylesheet

The whole site is built on one brand colour. If TRG ever wants to move from the
current blue toward the purple in the logo, that is a one-line change plus a
rebuild — ask and I will do it in a few minutes.

---

## 3. The contact form

**Enquiries land in two places, on purpose.**

1. An email to the address in **Customize → TRG company details → Main email
   address** (currently `info@trgnetworking.com`).
2. A record under **Enquiries** in the dashboard.

Both, every time. The form on the Hostinger build wrote to a hidden table and
emailed nobody, so visitors saw "Message Sent" while leads piled up where no one
was looking. Storing first means a mail outage can never lose a lead.

The **Emailed** column in the Enquiries list says whether the notification
actually went out. If it says **NO — not delivered**, the enquiry is still
there; only the email failed.

**It never claims success unless the email really left.** If sending fails, the
visitor sees a plain message with the phone number and email address instead of
a confirmation that is not true.

Other things it does: hitting reply in Outlook replies to the enquirer, not to
the website; a hidden field catches bots; five submissions per visitor per ten
minutes; and it works with JavaScript switched off.

### Worth checking after go-live

Send yourself a test enquiry from the live site and confirm it arrives. TRG's
mail is on Microsoft 365, and some hosts need an SMTP plugin before WordPress
can send at all. If the test does not arrive, tell me and I will set that up —
it is a ten-minute job, and the Enquiries list will hold everything in the
meantime.

---

## 4. The old URLs

www.trgnetworking.com currently has about sixty indexed URLs — twenty-five pages
and thirty-five blog posts — and none of them exist on this site. Left alone,
every one becomes a 404 the day you switch over, and the Google rankings behind
them go with it.

The plugin redirects all of them. `/network-security/` goes to `/cybersecurity/`,
`/about-us/` to `/about/`, `/our-clients/` to the case studies page, and so on.

`/managed-it-services/` is the same path on both sites, so its ranking carries
straight across with no redirect at all. `/support-center/` likewise — there is
a real page at that path on this site.

These run in PHP rather than in `.htaccess`, so they work the same on Apache,
IIS, nginx or LiteSpeed, and nobody has to edit a server config file on the live
host. They only fire on a URL WordPress could not otherwise answer, so a rule
can never shadow a page that exists.

**They were tested, not assumed.** Every one of the URLs was requested against a
real running copy of this site and its destination checked: forty-two cases, all
landing on a live page, no redirect loops.

### The thirty-five blog posts

Right now they all redirect to `/resources/`. That keeps them out of the 404 log
but it does not preserve any single post's ranking.

If a handful of those posts bring in real traffic, it is worth moving those
across as actual posts. Google Search Console will say which ones in about two
minutes. Tell me which posts matter and I will migrate them.

---

## 5. Search engines and social previews

Handled in the theme: page titles, a description per page, canonical URLs,
Open Graph and Twitter cards, and organisation markup for the company details.

Each page has a **Search engine description** box under the editor — the
sentence Google shows under the title.

If you later install Yoast or Rank Math, the theme steps aside completely rather
than writing a second, competing set of tags.

---

## 6. Going live on trgnetworking.com

**Read this before switching anything.**

### The one that would really hurt

TRG's email runs on Microsoft 365. The MX records for trgnetworking.com point at
`trgnetworking-com.mail.protection.outlook.com`. If the DNS is rebuilt from a
host's default template during the move, **company email stops** — not the
website, the email. Copy the existing MX, SPF, DKIM and any verification records
across before changing the A or CNAME record, and check them after.

### Sequence

1. Install the theme and plugin on a staging copy. Run TRG Setup.
2. Click through every page and read the words. Anything wrong, tell me.
3. Test the contact form on staging and confirm the email arrives.
4. Lower the DNS TTL on the current records to 300 seconds, a day ahead.
5. Move the site to the live host, keeping the old one running.
6. Repoint DNS. Confirm MX is untouched.
7. Request `/network-security/`, `/about-us/` and `/our-clients/` on the live
   domain and confirm each lands on the right page.
8. Send a real enquiry through the live form.
9. Submit `https://www.trgnetworking.com/sitemap.xml` in Google Search Console.
10. Leave the old site's files in place for a fortnight. It costs nothing and it
    is the difference between a rollback and a rebuild.

---

## 7. What is not built

Stated plainly rather than left for you to discover.

- **The three resource downloads do not exist.** The cards say "Coming soon"
  rather than linking to a file that is not there. Send me the PDFs and they
  become real downloads.
- **Healthcare and Nonprofits are sections on the Industries page**, not pages
  of their own. There was no copy for a full page on either source site, and
  writing it myself would have meant inventing claims about TRG.
- **No blog posts have been migrated.** See section 4.
- **There is no leadership section on the About page.** The change request asks
  About to communicate leadership. I will not invent names, titles, biographies
  or photographs of real people. Send me those and the section goes in.
- **The photography is what the Hostinger builder generated.** It is generic
  stock-style imagery, not photographs of TRG's actual team or office. Real
  photos would be an improvement whenever you have them.
- **Nothing on the site claims 99.9% uptime.** The change request rules that
  claim out unless documented data supports it, and it has not been restored.

### One thing that was fixed

The image the Hostinger build used as the footer logo was not a logo. It was a
stock photograph of five strangers in an office — with the stock library's
watermark still across the middle of it. It was appearing in the footer of every
page, on both builds.

It is gone. The footer now uses a white version of the real TRG wordmark,
derived from the logo file. Worth knowing in case that same watermarked image is
sitting anywhere else in the Hostinger account.

---

## 8. Section reference

Every section available in a page. Attributes are plain text.

| Shortcode | What it draws |
|---|---|
| `[trg_home_hero]` | The homepage hero: headline, buttons, badge pills, image with caption, floating cards, capability strip |
| `[trg_hero]` | An inner-page hero: eyebrow, heading, lede, buttons |
| `[trg_stats items="34+\|Years of experience;200+\|Organizations served"]` | The dark figures band |
| `[trg_partners title="…" items="Microsoft, Cisco, Dell"]` | The scrolling technology strip |
| `[trg_cards]…[trg_card icon="shield" title="…"]body[/trg_card]…[/trg_cards]` | A card grid. `columns="2\|3\|4"`, `bg="white\|canvas"` |
| `[trg_services]` | The service cards, from the Service cards menu |
| `[trg_industries]` | The numbered industry rows, from the Industry cards menu |
| `[trg_testimonials]` | The quotes, from the Testimonials menu |
| `[trg_media_split]` | Image one side, heading, tick list and button the other. `reverse="1"` flips it |
| `[trg_ai_panel]` | The dark numbered panel from the homepage |
| `[trg_perspective title="…" body="…"]` | The dark quote band |
| `[trg_cta_band]` | The closing blue call-to-action band. `button2_text="…"` adds a second button |
| `[trg_faq]…[trg_faq_item q="…"]answer[/trg_faq_item]…[/trg_faq]` | The questions accordion |
| `[trg_process columns="4"]…[trg_step n="1" title="…"]body[/trg_step]…[/trg_process]` | The numbered "what happens next" steps. `columns="3"` or `"4"` |
| `[trg_split_points]` | Heading one side, a grid of ticked points the other |
| `[trg_pills items="Azure, Teams, Intune"]` | Small rounded labels |
| `[trg_note title="…" button_text="…"]body[/trg_note]` | A bordered note panel |
| `[trg_contact_section]` | Contact details beside the enquiry form |
| `[trg_contact_form]` | The enquiry form on its own |
| `[trg_contact_details]` | The call / email / visit / existing-clients list |
| `[trg_support_cards]` | The Support Center cards and panel |
| `[trg_related type="service"]` | "Keep exploring" cards, leaving out the current page |

Two useful values anywhere a link is asked for: `phone` becomes the live phone
number as a dial link, and `email` becomes the live email address. Both read
from the Customizer, so a button can never disagree with the footer.

---

## 9. How it was checked

Not "it looked fine" — this is what was actually run, against a clean WordPress
installed from these two zip files:

- All twenty-seven pages loaded in a real browser: each has exactly one main
  heading, a real page title, real text, no broken images, no links that go
  nowhere, and no JavaScript errors.
- No sideways scrolling at 320px or 390px wide — the two widths that catch a
  broken phone layout.
- The contact form submitted end to end. The notification email was captured and
  read: right recipient, right subject, reply-to pointing at the enquirer.
- The failure path was forced as well, to confirm the form says so honestly
  instead of showing a false confirmation, keeps what the visitor typed, and
  still files the enquiry.
- The bot trap was submitted and confirmed to file nothing.
- Forty-four old URLs requested for real and their destinations checked.
- A missing URL returns a genuine 404, not a page pretending to be one.

---

## 10. Version 1.1 — the consolidated change request

Everything in the change request of 1 September has been applied. The parts
worth calling out:

### Applied

- **Homepage hero** — new headline, eyebrow kept, new body copy, "Talk with our
  team" and "Free IT Assessment", four floating callouts, four trust badges with
  "CMMC Readiness Experts". "Simpler IT. Stronger security. A team that
  responds." is preserved as the heading of the band below the figures.
- **Credibility band** — 34+ years, 200+ organizations, 7 solution areas,
  24x7 monitoring. The 99.9% uptime claim has not been restored.
- **Two new solution areas** — Network Infrastructure and Strategic IT / vCIO
  now have real pages, cards and menu entries. Their copy is written from the
  capability lists in the change request and nothing else.
- **New homepage sections** — Why TRG, Microsoft, and CMMC, each as one of the
  existing bands so the design language is unchanged.
- **CMMC terminology** — every page now says "prepare for" rather than
  "certify". The CMMC page states plainly that TRG is not a C3PAO, does not
  award or guarantee certification, and explains the difference between a TRG
  readiness assessment and a certification assessment.
- **Contact form** — the dropdown is now the five conversion paths from the
  change request, and the buttons deep-link to it, so "Free IT Assessment"
  lands on a form that already says Request an IT Assessment.
- **Conversion model** — the "what happens next" steps are now Consultation,
  Assessment, Discovery, Customized Solution.
- **Testimonials** — still only the two attributed quotes. Nothing invented,
  nothing restored. See below.
- **Design, layout, images, SEO and accessibility** are untouched. No band, CSS
  file, image or template was redesigned; this release changes words, ordering
  and two new pages.

### Waiting on TRG

1. **"7 Technology Solution Areas".** The change request asks for that figure in
   the credibility band, and also lists nine solution areas in the section
   directly below it — where nine cards now appear. As shipped the band says 7,
   exactly as requested. Tell me which number is right and it is a one-word fix.
2. **"200+ Organizations Served"**, "Microsoft Partner", "Women/Minority Owned"
   and "24x7 Support" are on the page as requested but are on your own list of
   claims to verify before production.
3. **Technology partners.** Pending confirmation of the current relationships,
   that band is headed "Technologies we work with every day" rather than
   "partners and alliances" — a statement about TRG rather than a claim made on
   another company's behalf. Send me the confirmed list and I will set it.
4. **Testimonials.** The two on the site — Nick Pirovolidis of BSC America and
   Todd Hirsch of Belt Built Contracting — were taken from TRG's own live site
   at www.trgnetworking.com, where they are published today. If that is not
   authorization enough, say so and I will remove them or drop the attributions.
5. **Leadership on the About page.** Asked for, not written: I will not invent
   people. Send names, titles and short biographies and it goes in.
6. **Contact details.** Phone, email and street address are unchanged from the
   previous build. Confirm them once and they update everywhere at the same
   time — see section 2.

import { Link } from 'react-router-dom'
import { ArrowRight, Quote } from 'lucide-react'
import Seo from '../components/Seo'
import { CtaBand, PageHero, Perspective, SectionHead } from '../components/Blocks'
import { addressLine, company, testimonials } from '../data/site'

/* -------------------------------------------------------------- Case studies */

export function CaseStudies() {
  return (
    <>
      <Seo
        title="Client Results | TRG Networking"
        description="What TRG Networking clients say about responsiveness, communication and cost-effective management of their IT."
      />
      <PageHero
        eyebrow="Client results"
        title="Real clients. Real relationships."
        lede="The clearest measure of an IT partner is whether clients stay — and whether they would say so out loud."
      />
      <section className="section bg-white">
        <div className="shell">
          <div className="grid gap-5 md:grid-cols-2">
            {testimonials.map((t) => (
              <figure key={t.name} className="card flex flex-col bg-canvas">
                <Quote size={26} className="text-brand-200" aria-hidden="true" />
                <blockquote className="mt-4 flex-1 text-[17px] leading-relaxed text-body">“{t.quote}”</blockquote>
                <figcaption className="mt-6 border-t border-line pt-4">
                  <span className="block font-heading text-[15px] font-bold text-ink">{t.name}</span>
                  <span className="block text-[13.5px] text-soft">{t.org}</span>
                </figcaption>
              </figure>
            ))}
          </div>

          {/* Stated plainly rather than padded out with invented case studies. */}
          <div className="mt-10 rounded-xl border border-line bg-canvas p-6">
            <h3 className="text-[17px]">Written case studies are in progress</h3>
            <p className="mt-2 max-w-2xl text-[15px] leading-relaxed text-muted">
              Detailed write-ups covering CMMC readiness, Azure migrations and managed IT transitions are
              being prepared with the clients involved. If you would like to speak to a reference in your
              industry, ask us and we will arrange an introduction.
            </p>
            <Link to="/contact" className="btn-primary mt-5">
              Ask for a reference <ArrowRight size={16} aria-hidden="true" />
            </Link>
          </div>
        </div>
      </section>
      <CtaBand />
    </>
  )
}

/* -------------------------------------------------------------------- Guides */

export function Guides() {
  const guides = [
    { title: 'IT & Security Health Checklist', body: 'Review support, security, backups and technology planning in one pass.' },
    { title: 'CMMC Readiness Checklist',       body: 'What government contractors should answer before technical remediation begins.' },
    { title: 'Secure AI Policy Starter',       body: 'Clarify approved tools, sensitive information and responsible employee use.' },
  ]
  return (
    <>
      <Seo
        title="IT Guides & Downloads | TRG Networking"
        description="Practical checklists on IT health, CMMC readiness and secure AI policy from TRG Networking."
      />
      <PageHero
        eyebrow="Guides & downloads"
        title="Practical checklists, not lead-magnet filler."
        lede="Short, usable documents built around the questions clients actually ask."
      />
      <section className="section bg-white">
        <div className="shell grid gap-5 sm:grid-cols-3">
          {guides.map((g) => (
            <div key={g.title} className="card flex flex-col">
              <h3 className="text-[17px]">{g.title}</h3>
              <p className="mt-2 flex-1 text-[15px] leading-relaxed text-muted">{g.body}</p>
              <p className="mt-4 inline-flex w-fit rounded-full bg-canvas px-3 py-1 font-heading text-[12px] font-bold uppercase tracking-wider text-soft">
                Coming soon
              </p>
            </div>
          ))}
        </div>
        <div className="shell mt-10">
          <p className="text-[15px] text-muted">
            Want one now? Call{' '}
            <a href={company.phoneHref} className="font-semibold text-brand-600 hover:underline">{company.phone}</a>{' '}
            and we will walk you through it.
          </p>
        </div>
      </section>
      <CtaBand />
    </>
  )
}

/* --------------------------------------------------------------- Legal pages */

function Legal({ title, eyebrow, lede, seoTitle, seoDesc, children }) {
  return (
    <>
      <Seo title={seoTitle} description={seoDesc} />
      <PageHero eyebrow={eyebrow} title={title} lede={lede} />
      <section className="section bg-white">
        <div className="shell max-w-3xl space-y-8 [&_h2]:text-[21px] [&_p]:mt-3 [&_p]:text-[15.5px] [&_p]:leading-relaxed [&_p]:text-muted [&_li]:text-[15.5px] [&_li]:leading-relaxed [&_li]:text-muted">
          {children}
        </div>
      </section>
      <CtaBand />
    </>
  )
}

export function Privacy() {
  return (
    <Legal
      eyebrow="Privacy"
      title="Privacy policy"
      lede="How TRG Networking handles the information you share through this website."
      seoTitle="Privacy Policy | TRG Networking"
      seoDesc="How TRG Networking collects, uses and protects information submitted through this website."
    >
      <div>
        <h2>Information we collect</h2>
        <p>
          When you submit the enquiry form on this site we collect the name, email address, company,
          phone number, service of interest and message you provide. We also record the date and the
          IP address the submission came from, which helps us block automated abuse.
        </p>
      </div>
      <div>
        <h2>How we use it</h2>
        <p>
          We use these details only to respond to your enquiry and to follow up about the services you
          asked about. We do not sell your information, and we do not add you to a marketing list
          without your agreement.
        </p>
      </div>
      <div>
        <h2>How it is stored</h2>
        <p>
          Enquiries are emailed to our team and retained in our business systems for as long as needed
          to serve you, and to meet our record-keeping obligations.
        </p>
      </div>
      <div>
        <h2>Third parties</h2>
        <p>
          This site loads web fonts from Google Fonts, which means Google receives the IP address of
          visitors. We do not run advertising trackers on this site.
        </p>
      </div>
      <div>
        <h2>Your choices</h2>
        <p>
          You can ask us what information we hold about you, ask us to correct it, or ask us to delete
          it. Contact{' '}
          <a href={`mailto:${company.email}`} className="font-semibold text-brand-600 hover:underline">{company.email}</a>{' '}
          or call{' '}
          <a href={company.phoneHref} className="font-semibold text-brand-600 hover:underline">{company.phone}</a>.
        </p>
      </div>
      <div>
        <h2>Contact</h2>
        <p>{company.legalName}, {addressLine}.</p>
      </div>
    </Legal>
  )
}

export function Terms() {
  return (
    <Legal
      eyebrow="Terms"
      title="Terms of use"
      lede="The terms that apply to your use of this website."
      seoTitle="Terms of Use | TRG Networking"
      seoDesc="Terms that apply to the use of the TRG Networking website."
    >
      <div>
        <h2>About this site</h2>
        <p>
          This website is operated by {company.legalName}. By using it you agree to these terms. If you
          do not agree, please do not use the site.
        </p>
      </div>
      <div>
        <h2>Information provided here</h2>
        <p>
          Content on this site describes our services in general terms. It is not technical, legal or
          compliance advice, and it does not create a client relationship. Descriptions of CMMC and
          other regulatory work describe readiness support — they are not a guarantee of certification
          or of a compliance outcome.
        </p>
      </div>
      <div>
        <h2>Accuracy</h2>
        <p>
          We work to keep this site accurate and current, but we do not warrant that every page is free
          of error or omission. Service details, availability and scope may change.
        </p>
      </div>
      <div>
        <h2>Intellectual property</h2>
        <p>
          The text, images, logos and design on this site belong to {company.legalName} or are used with
          permission. Please do not reproduce them without our written agreement.
        </p>
      </div>
      <div>
        <h2>External links</h2>
        <p>
          This site links to systems and resources we do not control, including our client support
          center. We are not responsible for the content or availability of external sites.
        </p>
      </div>
      <div>
        <h2>Questions</h2>
        <p>
          Contact{' '}
          <a href={`mailto:${company.email}`} className="font-semibold text-brand-600 hover:underline">{company.email}</a>.
        </p>
      </div>
    </Legal>
  )
}

export function Accessibility() {
  return (
    <Legal
      eyebrow="Accessibility"
      title="A website designed to welcome more people."
      lede="We want this site to be usable by everyone, including people using screen readers, keyboard navigation or magnification."
      seoTitle="Accessibility | TRG Networking"
      seoDesc="TRG Networking's commitment to an accessible website, and how to report a barrier."
    >
      <div>
        <h2>What we have done</h2>
        <ul className="mt-3 list-disc space-y-2 pl-5">
          <li>Every page can be reached and operated with a keyboard alone, and focus is always visible.</li>
          <li>A “Skip to content” link is the first thing a keyboard or screen reader user encounters.</li>
          <li>Headings follow a logical order, and each page has a single main heading.</li>
          <li>Images that carry meaning have text alternatives; decorative graphics are hidden from screen readers.</li>
          <li>Body text meets the WCAG 2.1 AA contrast ratio against its background.</li>
          <li>The layout reflows without horizontal scrolling down to a 320px-wide screen.</li>
          <li>Animation is disabled automatically for visitors whose system requests reduced motion.</li>
          <li>The enquiry form uses real labels, and errors are announced rather than shown by colour alone.</li>
        </ul>
      </div>
      <div>
        <h2>Where we are</h2>
        <p>
          We aim to meet WCAG 2.1 Level AA. Accessibility is ongoing rather than a one-time project, and
          we review new pages as they are added.
        </p>
      </div>
      <div>
        <h2>Tell us about a barrier</h2>
        <p>
          If any part of this site is difficult to use, please tell us — it is the fastest way for us to
          fix it. Email{' '}
          <a href={`mailto:${company.email}`} className="font-semibold text-brand-600 hover:underline">{company.email}</a>{' '}
          or call{' '}
          <a href={company.phoneHref} className="font-semibold text-brand-600 hover:underline">{company.phone}</a>{' '}
          and we will respond and offer the information you needed in another format.
        </p>
      </div>
    </Legal>
  )
}

/* ----------------------------------------------------------------------- 404 */

export function NotFound() {
  return (
    <>
      <Seo title="Page not found | TRG Networking" description="That page could not be found." />
      <section className="section bg-white">
        <div className="shell max-w-xl py-16 text-center">
          <p className="font-display text-[68px] font-extrabold leading-none text-brand-200">404</p>
          <h1 className="mt-4 text-[30px]">We could not find that page.</h1>
          <p className="mt-4 text-[16px] leading-relaxed text-muted">
            The link may be out of date. Try our services, or tell us what you were looking for and we
            will point you at it.
          </p>
          <div className="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <Link to="/" className="btn-primary">Back to home</Link>
            <Link to="/services" className="btn-outline">Browse services</Link>
          </div>
        </div>
      </section>
    </>
  )
}

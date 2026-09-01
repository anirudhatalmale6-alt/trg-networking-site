import { useEffect } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import { ExternalLink, Mail, MapPin, Phone } from 'lucide-react'
import Seo from '../components/Seo'
import ContactForm from '../components/ContactForm'
import { CtaBand, PageHero, Perspective, SectionHead } from '../components/Blocks'
import { addressLine, company, processSteps } from '../data/site'

export default function Contact() {
  const [params] = useSearchParams()
  const isAssessment = params.get('type') === 'assessment'

  // "Free IT Assessment" links across the site arrive with ?type=assessment.
  useEffect(() => { window.scrollTo({ top: 0 }) }, [])

  return (
    <>
      <Seo
        title="Contact TRG Networking | Schedule a Consultation"
        description="Talk with TRG Networking about managed IT, cybersecurity, Microsoft 365, CMMC readiness or secure AI adoption. Call 410-363-6980 or send us a message."
      />

      <PageHero
        eyebrow="Talk with our team"
        title={isAssessment ? 'Request your free IT assessment.' : 'Start with a straightforward conversation.'}
        lede="Tell us what is working, what is frustrating your team and what you want technology to do better. No technical preparation required."
      />

      <section className="section bg-white">
        <div className="shell grid gap-10 lg:grid-cols-[1fr_1.1fr] lg:gap-14">
          <div>
            <SectionHead
              title="No technical preparation required."
              body="Whether you are replacing an IT provider, strengthening cybersecurity, preparing for CMMC, reviewing Microsoft 365 or exploring AI, we will help identify a sensible next step."
              align="left"
            />

            <ul className="mt-9 space-y-4">
              <li className="flex items-start gap-4 rounded-xl border border-line bg-canvas p-5">
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600" aria-hidden="true">
                  <Phone size={18} />
                </span>
                <span className="min-w-0">
                  <span className="block font-heading text-[15px] font-bold text-ink">Call</span>
                  <a href={company.phoneHref} className="text-[15px] text-brand-600 hover:underline">
                    {company.phone}
                  </a>
                </span>
              </li>

              <li className="flex items-start gap-4 rounded-xl border border-line bg-canvas p-5">
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600" aria-hidden="true">
                  <Mail size={18} />
                </span>
                <span className="min-w-0">
                  <span className="block font-heading text-[15px] font-bold text-ink">Email</span>
                  <a href={`mailto:${company.email}`} className="break-all text-[15px] text-brand-600 hover:underline">
                    {company.email}
                  </a>
                </span>
              </li>

              <li className="flex items-start gap-4 rounded-xl border border-line bg-canvas p-5">
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600" aria-hidden="true">
                  <MapPin size={18} />
                </span>
                <span className="min-w-0">
                  <span className="block font-heading text-[15px] font-bold text-ink">Visit</span>
                  <span className="text-[15px] text-muted">{addressLine}</span>
                </span>
              </li>

              <li className="flex items-start gap-4 rounded-xl border border-brand-200 bg-brand-50 p-5">
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-brand-600" aria-hidden="true">
                  <ExternalLink size={18} />
                </span>
                <span className="min-w-0">
                  <span className="block font-heading text-[15px] font-bold text-ink">Existing clients</span>
                  <span className="text-[14.5px] text-muted">
                    Please use the{' '}
                    <Link to={company.supportUrl} className="font-semibold text-brand-600 hover:underline">
                      Support Center
                    </Link>{' '}
                    for active technical requests.
                  </span>
                </span>
              </li>
            </ul>
          </div>

          <div>
            <ContactForm />
          </div>
        </div>
      </section>

      <section className="section bg-canvas">
        <div className="shell">
          <SectionHead eyebrow="What happens next" title="Ready to begin?" />
          <ol className="mt-12 grid gap-6 md:grid-cols-3">
            {processSteps.map((s) => (
              <li key={s.n} className="text-center">
                <span className="mx-auto flex h-11 w-11 items-center justify-center rounded-full border-2 border-brand-200 bg-white font-display text-[16px] font-extrabold text-brand-600">
                  {s.n}
                </span>
                <h3 className="mt-4 text-[18px]">{s.title}</h3>
                <p className="mx-auto mt-2 max-w-xs text-[15px] leading-relaxed text-muted">{s.body}</p>
              </li>
            ))}
          </ol>
        </div>
      </section>

      <Perspective
        eyebrow="TRG perspective"
        title="A conversation costs nothing and usually clarifies a lot."
        body={`Call ${company.phone} or send the form above. We will listen first, then suggest a sensible next step — whether or not that step involves TRG.`}
      />
    </>
  )
}

/* --------------------------------------------------------------- Resources */

const resources = [
  { title: 'IT & Security Health Checklist', body: 'A practical starting point for reviewing support, security, backups and technology planning.' },
  { title: 'CMMC Readiness Checklist',       body: 'Questions government contractors should answer before technical remediation begins.' },
  { title: 'Secure AI Policy Starter',       body: 'A framework for clarifying approved tools, sensitive information and responsible employee use.' },
  { title: 'Technology Insights',            body: 'Original articles that connect changing technology to practical business decisions.' },
]

export function Resources() {
  return (
    <>
      <Seo
        title="Business IT Resources | TRG Networking"
        description="Practical technology guidance for business leaders — checklists and insights on IT support, cybersecurity, Microsoft 365, CMMC and AI."
      />
      <PageHero
        eyebrow="Resources"
        title="Practical technology guidance for business leaders."
        lede="Clear explanations, useful checklists and timely insights — without unnecessary jargon or fear-based selling."
      />
      <section className="section bg-white">
        <div className="shell">
          <SectionHead
            title="Useful content should help someone make a better decision."
            body="TRG's resource library prioritizes original guidance built around the questions clients actually ask about IT support, cybersecurity, Microsoft 365, CMMC and AI."
          />
          <div className="mt-12 grid gap-5 sm:grid-cols-2">
            {resources.map((r) => (
              <div key={r.title} className="card flex flex-col">
                <h3 className="text-[18px]">{r.title}</h3>
                <p className="mt-2 flex-1 text-[15px] leading-relaxed text-muted">{r.body}</p>
                {/* No download link is shown until a real file exists — a
                    button that goes nowhere reads as a broken feature. */}
                <p className="mt-4 inline-flex w-fit rounded-full bg-canvas px-3 py-1 font-heading text-[12px] font-bold uppercase tracking-wider text-soft">
                  Coming soon
                </p>
              </div>
            ))}
          </div>
          <div className="mt-10 flex flex-wrap gap-3">
            <Link to="/resources/case-studies" className="btn-outline">Case studies</Link>
            <Link to="/resources/guides" className="btn-outline">Guides &amp; downloads</Link>
          </div>
        </div>
      </section>
      <Perspective
        title="Want one of these before it is published?"
        body={`Ask us. Call ${company.phone} and we will walk you through the checklist on the phone rather than making you wait for a download.`}
      />
      <CtaBand />
    </>
  )
}

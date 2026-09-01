import { Link } from 'react-router-dom'
import { ArrowRight, ArrowUpRight, Check } from 'lucide-react'
import Seo from '../components/Seo'
import {
  CtaBand, FeatureGrid, IconTile, PageHero, Perspective, SectionHead,
} from '../components/Blocks'
import { company, industries, services, whyTrgPoints } from '../data/site'

/* ------------------------------------------------------------------ Services */

export function Services() {
  return (
    <>
      <Seo
        title="Technology Services | TRG Networking"
        description="Managed IT, help desk support, cybersecurity, Microsoft 365 and Azure, secure AI adoption, CMMC readiness and business continuity from TRG Networking."
      />
      <PageHero
        eyebrow="Complete technology care"
        title="Every layer of your technology, working together."
        lede="From daily support to long-term strategy, TRG connects the pieces so your technology works as one secure, reliable system."
      />
      <section className="section bg-white">
        <div className="shell grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {services.map((s) => (
            <Link key={s.to} to={s.to} className="card-hover group flex flex-col">
              <h3 className="text-[18px] group-hover:text-brand-600">{s.title}</h3>
              <p className="mt-2 flex-1 text-[15px] leading-relaxed text-muted">{s.body}</p>
              <span className="mt-4 inline-flex items-center gap-1.5 font-heading text-[13.5px] font-bold text-brand-600">
                Learn more
                <ArrowRight size={14} className="transition-transform group-hover:translate-x-1" aria-hidden="true" />
              </span>
            </Link>
          ))}
        </div>
      </section>
      <Perspective
        title="One partner is easier than five vendors."
        body="When support, security, Microsoft and planning sit with the same team, problems stop falling between the gaps — and nobody has to referee."
      />
      <CtaBand />
    </>
  )
}

/* ---------------------------------------------------------------- Industries */

const EXTRA = {
  healthcare: {
    title: 'Healthcare',
    lede: 'HIPAA compliance, EHR support and clinical IT',
    body: 'Protect patient information and keep clinical systems dependable for the people who rely on them. TRG supports practices with security, access control, backup and responsive help for staff who cannot afford to wait.',
    points: ['HIPAA-aware security controls', 'EHR and clinical application support', 'Secure remote and multi-site access', 'Backup and recovery for patient data'],
  },
  nonprofits: {
    title: 'Nonprofits',
    lede: 'Affordable, mission-aligned technology',
    body: 'Affordable, mission-aligned technology that stretches limited budgets without cutting corners on security. TRG helps nonprofits get more from donated and discounted Microsoft licensing while keeping donor and constituent data protected.',
    points: ['Nonprofit Microsoft licensing guidance', 'Donor and constituent data protection', 'Predictable, budget-aware planning', 'Support for volunteers and hybrid staff'],
  },
}

export function Industries() {
  return (
    <>
      <Seo
        title="Industries We Serve | TRG Networking"
        description="IT and cybersecurity for construction, manufacturing, government contractors, professional services, healthcare and nonprofits."
      />
      <PageHero
        eyebrow="Industries we serve"
        title="Technology aligned with how your organization works."
        lede="Technology decisions are better when they reflect your operations, risks, customers and compliance responsibilities."
      />

      <section className="section bg-white">
        <div className="shell">
          <ul className="divide-y divide-line border-y border-line">
            {industries.map((ind) => (
              <li key={ind.title}>
                <Link
                  to={ind.to}
                  className="group flex flex-col gap-3 py-6 transition-colors hover:bg-canvas sm:flex-row sm:items-center sm:gap-8 sm:px-4"
                >
                  <span className="font-display text-[15px] font-extrabold text-brand-600 sm:w-10">{ind.n}</span>
                  <span className="min-w-0 sm:w-[290px] sm:shrink-0">
                    <span className="block font-heading text-[18px] font-extrabold text-ink group-hover:text-brand-600">
                      {ind.title}
                    </span>
                    <span className="mt-0.5 block text-[13px] text-soft">{ind.tags}</span>
                  </span>
                  <span className="min-w-0 flex-1 text-[15px] leading-relaxed text-muted">{ind.body}</span>
                  <ArrowUpRight
                    size={19}
                    className="shrink-0 text-soft transition-all group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-brand-600"
                    aria-hidden="true"
                  />
                </Link>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* Healthcare and nonprofits are covered in full here rather than linking
          to a page that does not exist. */}
      {Object.entries(EXTRA).map(([id, s], i) => (
        <section key={id} id={id} className={`section ${i % 2 === 0 ? 'bg-canvas' : 'bg-white'}`}>
          <div className="shell grid gap-10 lg:grid-cols-[1fr_1.2fr] lg:gap-14">
            <SectionHead eyebrow={s.lede} title={s.title} body={s.body} align="left" />
            <ul className="grid gap-3 sm:grid-cols-2">
              {s.points.map((p) => (
                <li key={p} className="flex items-start gap-3 rounded-xl border border-line bg-white p-4">
                  <Check size={16} className="mt-0.5 shrink-0 text-brand-600" aria-hidden="true" />
                  <span className="text-[14.5px] leading-relaxed text-body">{p}</span>
                </li>
              ))}
            </ul>
          </div>
        </section>
      ))}

      <CtaBand />
    </>
  )
}

/* ------------------------------------------------------------------ Why TRG */

export function WhyTrg() {
  return (
    <>
      <Seo
        title="Why Choose TRG Networking"
        description="Responsive by design, plain-English answers, business-minded guidance and long-term relationships — the standards behind the way TRG Networking works since 1992."
      />
      <PageHero
        eyebrow="Why TRG"
        title="Experienced enough to guide. Personal enough to care."
        lede={`Since ${company.founded}, TRG has built long-term relationships through responsiveness, integrity and practical technology guidance.`}
      />
      <section className="section bg-white">
        <div className="shell">
          <SectionHead
            title="We keep clients through service — not by keeping them in the dark."
            body="TRG believes you should understand your technology, know what you are paying for and retain appropriate documentation about your environment. Trust is earned through consistent action."
          />
          <div className="mt-12">
            <FeatureGrid
              items={[
                { title: 'Responsive by design',    body: 'Multiple team members oversee incoming support so requests receive attention and follow-through.' },
                { title: 'Plain-English answers',   body: 'We explain technology without making employees or leadership feel talked down to.' },
                { title: 'Business-minded guidance', body: 'Recommendations consider cost, risk, usability, operations and long-term value.' },
                { title: 'Seasoned professionals',  body: 'Experienced technical people work together to solve issues and plan improvements.' },
                { title: 'Proactive care',          body: 'Monitoring and maintenance focus on preventing disruption, not simply reacting to it.' },
                { title: 'Long-term relationships', body: 'We aim to become a trusted extension of the organizations we support.' },
              ]}
            />
          </div>
        </div>
      </section>

      <section className="section bg-canvas">
        <div className="shell">
          <SectionHead eyebrow="What that means in practice" title="The standards behind the work." />
          <div className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {whyTrgPoints.map((p) => (
              <div key={p.title} className="card">
                <IconTile><Check size={20} /></IconTile>
                <h3 className="mt-4 text-[17px]">{p.title}</h3>
                <p className="mt-2 text-[15px] leading-relaxed text-muted">{p.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Perspective
        title="Every request is seen. Every solution is explained."
        body="Every recommendation should serve the business. That is the standard behind the way TRG works."
      />
      <CtaBand />
    </>
  )
}

/* -------------------------------------------------------------------- About */

export function About() {
  return (
    <>
      <Seo
        title="About TRG Networking"
        description="TRG Networking is headquartered in Columbia, Maryland and has supported small and midsize organizations with managed IT, cybersecurity and Microsoft solutions since 1992."
      />
      <PageHero
        eyebrow="About TRG Networking"
        title={`Technology has changed since ${company.founded}. Our commitment has not.`}
        lede="TRG helps organizations use technology confidently through experienced support, thoughtful security and genuine long-term partnership."
      />
      <section className="section bg-white">
        <div className="shell grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
          <img
            src="/img/about-team.webp"
            alt="The TRG Networking team collaborating with a client"
            width="973" height="404"
            loading="lazy"
            className="w-full rounded-2xl object-cover shadow-[0_24px_60px_-28px_rgba(15,23,42,0.4)]"
          />
          <SectionHead
            title="Maryland roots. Nationwide support."
            body="TRG Networking is headquartered in Columbia, Maryland and supports small and midsize organizations locally and across the country. Our work spans managed IT, cybersecurity, Microsoft 365 and Azure, CMMC readiness and secure AI adoption."
            align="left"
          />
        </div>
      </section>

      <section className="section bg-canvas">
        <div className="shell">
          <FeatureGrid
            columns={2}
            items={[
              { title: 'Our purpose',    body: "Make technology simpler to manage, safer to use and better aligned with each client's business." },
              { title: 'Our approach',   body: 'Listen first, communicate clearly, recommend responsibly and follow through on the work.' },
              { title: 'Our experience', body: 'More than three decades of adapting to major technology change while supporting real business operations.' },
              { title: 'Our location',   body: `Based at ${company.address.street}, ${company.address.city}, ${company.address.state} ${company.address.zip}.` },
            ]}
          />
        </div>
      </section>

      <Perspective
        title="The right technology relationship should reduce stress — not create more of it."
        body="TRG works to give leadership and employees confidence that their technology has an experienced team behind it."
      />
      <CtaBand />
    </>
  )
}

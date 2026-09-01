import { Link } from 'react-router-dom'
import {
  ArrowDown, ArrowRight, ArrowUpRight, BadgeCheck, Check, CloudCog, Cloud,
  Database, Headset, Map, Quote, Server, Shield, Sparkles, Users, Activity,
} from 'lucide-react'
import Seo from '../components/Seo'
import {
  CtaBand, IconTile, Perspective, Pills, SectionHead,
} from '../components/Blocks'
import {
  aiSteps, aiTags, company, heroBadges, heroFloatCards, industries, outcomes,
  partners, processSteps, services, stats, testimonials, trustStrip,
} from '../data/site'

const ICONS = {
  server: Server, shield: Shield, cloud: Cloud, sparkles: Sparkles,
  database: Database, badge: BadgeCheck, headset: Headset, cloudcog: CloudCog,
  activity: Activity, users: Users, map: Map,
}

function Icon({ name, size = 20 }) {
  const C = ICONS[name] || Check
  return <C size={size} aria-hidden="true" />
}

export default function Home() {
  return (
    <>
      <Seo
        title="TRG Networking | Managed IT, Cybersecurity & Microsoft Solutions"
        description="Managed IT, cybersecurity, Microsoft 365 and Azure, CMMC readiness and secure AI adoption from a Maryland-based team supporting organizations nationwide. Trusted since 1992."
        image="/img/hero-team.webp"
      />

      {/* ------------------------------------------------------------ hero */}
      <section className="relative overflow-hidden">
        <div
          className="pointer-events-none absolute inset-0"
          style={{ background: 'linear-gradient(160deg, #FFFFFF 0%, #F5F9FF 40%, #EFF6FF 100%)' }}
          aria-hidden="true"
        />
        <div
          className="pointer-events-none absolute inset-0"
          style={{ background: 'radial-gradient(ellipse 70% 55% at 72% 40%, rgba(37,99,235,0.10) 0%, transparent 65%)' }}
          aria-hidden="true"
        />

        <div className="shell relative grid items-center gap-12 py-14 sm:py-16 lg:grid-cols-2 lg:gap-14 lg:py-20">
          <div className="animate-fadeUp">
            <span className="eyebrow-pill">Trusted technology partner since {company.founded}</span>

            <h1 className="mt-6 text-[36px] leading-[1.06] sm:text-[52px] lg:text-[58px]">
              Simpler IT.<br />
              Stronger security.<br />
              <span className="text-brand-600">A team that responds.</span>
            </h1>

            <p className="mt-6 max-w-xl text-[18px] leading-relaxed text-muted">
              TRG Networking helps organizations modernize, secure and grow through managed IT,
              Microsoft Azure and 365, cybersecurity, CMMC readiness and secure AI — delivered by a
              Maryland-based team supporting businesses nationwide.
            </p>

            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
              <Link to="/contact" className="btn-primary">
                Talk with our team <ArrowRight size={16} aria-hidden="true" />
              </Link>
              <Link to="/contact?type=assessment" className="btn-outline">
                Free IT assessment
              </Link>
            </div>

            <ul className="mt-8 flex flex-wrap gap-2">
              {heroBadges.map((b) => (
                <li
                  key={b}
                  className="inline-flex items-center gap-1.5 rounded-full border border-line bg-white
                             px-3 py-1.5 text-[13px] font-medium text-muted shadow-sm"
                >
                  <Check size={13} className="text-brand-600" aria-hidden="true" />
                  {b}
                </li>
              ))}
            </ul>

            <a
              href="#services"
              className="mt-8 inline-flex items-center gap-2 font-heading text-[14px] font-semibold text-brand-600 hover:text-brand-700"
            >
              Explore our services <ArrowDown size={15} aria-hidden="true" />
            </a>
          </div>

          {/* The floating cards sit INSIDE the image frame at every width.
              On the Lovable build they were absolutely positioned against the
              viewport and hung off the right edge of a phone screen. */}
          <div className="relative">
            <div className="relative overflow-hidden rounded-2xl shadow-[0_30px_70px_-30px_rgba(15,23,42,0.45)]">
              <img
                src="/img/hero-team.webp"
                alt="A TRG Networking consultant working through a technology plan with a client team"
                width="1376" height="768"
                fetchPriority="high"
                className="aspect-[16/10] w-full object-cover"
              />
              <div
                className="pointer-events-none absolute inset-x-0 bottom-0 h-2/5"
                style={{ background: 'linear-gradient(to top, rgba(15,23,42,0.85), transparent)' }}
                aria-hidden="true"
              />
              <div className="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                <p className="font-heading text-[10.5px] font-bold uppercase tracking-[0.16em] text-brand-200">
                  The TRG difference
                </p>
                <p className="mt-1.5 font-heading text-[19px] font-extrabold leading-tight text-white sm:text-[22px]">
                  Technology that feels more human.
                </p>
              </div>
            </div>

            <ul className="mt-4 grid gap-3 sm:grid-cols-2">
              {heroFloatCards.map((c) => (
                <li key={c.eyebrow} className="flex items-start gap-3 rounded-xl border border-line bg-white p-3.5 shadow-sm">
                  <span
                    className="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-brand-50 text-brand-600"
                    aria-hidden="true"
                  >
                    <Check size={15} />
                  </span>
                  <span className="min-w-0">
                    <span className="block font-heading text-[10.5px] font-bold uppercase tracking-[0.14em] text-soft">
                      {c.eyebrow}
                    </span>
                    <span className="block text-[14px] font-semibold text-ink">{c.title}</span>
                  </span>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Capability strip from the Lovable build. */}
        <div className="relative border-y border-line bg-white/70">
          <div className="shell flex flex-wrap items-center justify-center gap-x-8 gap-y-2.5 py-4">
            {trustStrip.map((t) => (
              <span
                key={t}
                className="font-heading text-[11px] font-bold uppercase tracking-[0.16em] text-soft"
              >
                {t}
              </span>
            ))}
          </div>
        </div>
      </section>

      {/* ----------------------------------------------------------- stats */}
      <section className="bg-ink py-12">
        <div className="shell grid grid-cols-2 gap-8 lg:grid-cols-4">
          {stats.map((s) => (
            <div key={s.label} className="text-center">
              <div className="font-display text-[34px] font-extrabold leading-none text-white sm:text-[40px]">
                {s.value}
              </div>
              <div className="mt-2 text-[13.5px] text-white/60">{s.label}</div>
            </div>
          ))}
        </div>
      </section>

      {/* -------------------------------------------------------- partners */}
      <section className="border-b border-line bg-white py-12">
        <div className="shell">
          <p className="text-center font-heading text-[11px] font-bold uppercase tracking-[0.16em] text-soft">
            Technology partners &amp; alliances
          </p>
          {/* Overflow is clipped by the parent so the marquee can never make
              the page scroll sideways. */}
          <div className="relative mt-7 overflow-hidden">
            <div className="flex w-max animate-marquee gap-3">
              {[...partners, ...partners].map((p, i) => (
                <span
                  key={p + i}
                  aria-hidden={i >= partners.length}
                  className="flex h-11 shrink-0 items-center rounded-lg border border-line bg-canvas px-6
                             font-heading text-[14px] font-bold text-soft"
                >
                  {p}
                </span>
              ))}
            </div>
            <div className="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-white to-transparent" aria-hidden="true" />
            <div className="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-white to-transparent" aria-hidden="true" />
          </div>
        </div>
      </section>

      {/* -------------------------------------------------------- outcomes */}
      <section className="section bg-canvas">
        <div className="shell">
          <SectionHead
            eyebrow="Technology should move your business forward"
            title="Less disruption. More confidence."
            body="TRG makes technology easier to manage, easier to understand and better aligned with the way your organization actually works."
          />
          <div className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {outcomes.map((o) => (
              <div key={o.title} className="card-hover">
                <IconTile><Icon name={o.icon} /></IconTile>
                <h3 className="mt-4 text-[17px]">{o.title}</h3>
                <p className="mt-2 text-[15px] leading-relaxed text-muted">{o.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* -------------------------------------------------------- services */}
      <section id="services" className="section bg-white">
        <div className="shell">
          <SectionHead
            eyebrow="Complete technology care"
            title="One team. Every layer covered."
            body="From daily support to long-term strategy, TRG connects the pieces so your technology works as one secure, reliable system."
            pill
          />
          <div className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {services.map((s) => (
              <Link key={s.to} to={s.to} className="card-hover group flex flex-col">
                <IconTile><Icon name={s.icon} /></IconTile>
                <h3 className="mt-4 text-[18px]">{s.title}</h3>
                <p className="mt-2 flex-1 text-[15px] leading-relaxed text-muted">{s.body}</p>
                <span className="mt-4 inline-flex items-center gap-1.5 font-heading text-[13.5px] font-bold text-brand-600">
                  Learn more
                  <ArrowRight size={14} className="transition-transform group-hover:translate-x-1" aria-hidden="true" />
                </span>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* -------------------------------------------------- responsive band */}
      <section className="section bg-canvas">
        <div className="shell grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
          <div className="relative order-2 lg:order-1">
            <img
              src="/img/lov-support.webp"
              alt="A TRG IT specialist working alongside a client team member"
              width="1400" height="1050"
              loading="lazy"
              className="aspect-[4/3] w-full rounded-2xl object-cover shadow-[0_24px_60px_-28px_rgba(15,23,42,0.45)]"
            />
            <div className="mt-4 flex items-start gap-3 rounded-xl border border-line bg-white p-4 shadow-sm sm:absolute sm:-bottom-6 sm:left-6 sm:mt-0 sm:max-w-[280px]">
              <span className="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600" aria-hidden="true">
                <Users size={16} />
              </span>
              <span className="min-w-0">
                <span className="block font-heading text-[14px] font-bold text-ink">Shared oversight</span>
                <span className="block text-[13px] leading-snug text-muted">
                  Multiple team members help keep requests moving.
                </span>
              </span>
            </div>
          </div>

          <div className="order-1 lg:order-2">
            <SectionHead
              eyebrow="Responsive by design"
              title="Multiple eyes on every request. One team accountable."
              body="Your support request should never feel lost in a queue. Multiple people across TRG oversee incoming requests, help ensure the right person is assigned and keep work moving toward resolution."
              align="left"
            />
            <ul className="mt-7 space-y-3">
              {[
                'Human attention — not an anonymous call center',
                'Clear ownership and follow-through',
                'Plain-English communication throughout',
              ].map((t) => (
                <li key={t} className="flex items-start gap-3 text-[15.5px] text-body">
                  <Check size={17} className="mt-0.5 shrink-0 text-brand-600" aria-hidden="true" />
                  {t}
                </li>
              ))}
            </ul>
            <Link to="/help-desk-it-support" className="btn-primary mt-8">
              Meet your responsive IT team <ArrowRight size={16} aria-hidden="true" />
            </Link>
          </div>
        </div>
      </section>

      {/* ------------------------------------------------------ industries */}
      <section className="section bg-white">
        <div className="shell">
          <SectionHead
            eyebrow="Experience that fits your world"
            title="We learn how your business works."
            body="Technology decisions are better when they reflect your operations, risks, customers and compliance responsibilities."
            pill
          />
          <ul className="mt-12 divide-y divide-line border-y border-line">
            {industries.map((ind) => (
              <li key={ind.title}>
                <Link
                  to={ind.to}
                  className="group flex flex-col gap-3 py-6 transition-colors hover:bg-canvas
                             sm:flex-row sm:items-center sm:gap-8 sm:px-4"
                >
                  <span className="font-display text-[15px] font-extrabold text-brand-600 sm:w-10">
                    {ind.n}
                  </span>
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

      {/* -------------------------------------------------------------- AI */}
      <section className="section bg-canvas">
        <div className="shell grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
          <div>
            <SectionHead
              eyebrow="Secure AI adoption"
              title={<>Use AI with a plan.<br /><span className="text-brand-600">Not a free-for-all.</span></>}
              body="TRG helps your organization adopt AI responsibly — protecting company information while giving employees practical ways to work faster and make better decisions."
              align="left"
            />
            <div className="mt-7">
              <Pills items={aiTags} />
            </div>
            <Link to="/secure-ai-adoption" className="btn-primary mt-8">
              Explore secure AI services <ArrowRight size={16} aria-hidden="true" />
            </Link>
          </div>

          <div className="relative overflow-hidden rounded-2xl bg-ink p-6 sm:p-7">
            <div
              className="pointer-events-none absolute inset-0"
              style={{ background: 'radial-gradient(ellipse 70% 60% at 80% 10%, rgba(37,99,235,0.35) 0%, transparent 70%)' }}
              aria-hidden="true"
            />
            <div className="relative">
              <div className="flex items-center justify-between border-b border-white/10 pb-4">
                <span className="font-heading text-[10.5px] font-bold uppercase tracking-[0.16em] text-white/55">
                  TRG / AI enablement
                </span>
                <span className="h-2 w-2 rounded-full bg-emerald-400" aria-hidden="true" />
              </div>
              <ul className="divide-y divide-white/10">
                {aiSteps.map((s) => (
                  <li key={s.n} className="flex items-center gap-4 py-4">
                    <span className="font-display text-[13px] font-bold text-white/40">{s.n}</span>
                    <span className="min-w-0 flex-1">
                      <span className="block font-heading text-[15px] font-bold text-white">{s.title}</span>
                      <span className="block text-[13px] text-white/55">{s.sub}</span>
                    </span>
                    <span
                      className="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white/10 text-white/70"
                      aria-hidden="true"
                    >
                      <Check size={13} />
                    </span>
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* ---------------------------------------------------- testimonials */}
      <section className="section bg-white">
        <div className="shell">
          <SectionHead
            eyebrow="Trusted relationships"
            title="Technology expertise. A genuinely personal approach."
            body={`Since ${company.founded}, organizations have trusted TRG to care for critical systems, support their people and explain complex decisions without the jargon.`}
            pill
          />
          <div className="mt-12 grid gap-5 md:grid-cols-2">
            {testimonials.map((t) => (
              <figure key={t.name} className="card flex flex-col bg-canvas">
                <Quote size={26} className="text-brand-200" aria-hidden="true" />
                <blockquote className="mt-4 flex-1 text-[17px] leading-relaxed text-body">
                  “{t.quote}”
                </blockquote>
                <figcaption className="mt-6 border-t border-line pt-4">
                  <span className="block font-heading text-[15px] font-bold text-ink">{t.name}</span>
                  <span className="block text-[13.5px] text-soft">{t.org}</span>
                </figcaption>
              </figure>
            ))}
          </div>
          <div className="mt-10 text-center">
            <Link to="/why-trg" className="btn-outline">
              Why businesses choose TRG <ArrowRight size={16} aria-hidden="true" />
            </Link>
          </div>
        </div>
      </section>

      {/* --------------------------------------------------------- process */}
      <section className="section bg-canvas">
        <div className="shell">
          <SectionHead
            eyebrow="A simple place to begin"
            title="Start with a conversation."
            body="No technical preparation required. Tell us what is working, what is frustrating your team and what you want technology to do better."
          />
          <ol className="mt-12 grid gap-6 md:grid-cols-3">
            {processSteps.map((s) => (
              <li key={s.n} className="relative text-center">
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
        title="The right technology relationship should reduce stress — not create more of it."
        body="TRG works to give leadership and employees confidence that their technology has an experienced team behind it."
      />

      <CtaBand />
    </>
  )
}

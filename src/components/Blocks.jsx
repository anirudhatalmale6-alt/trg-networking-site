import { useState } from 'react'
import { Link } from 'react-router-dom'
import { ArrowRight, Check, Minus, Plus } from 'lucide-react'
import { company } from '../data/site'

/** Eyebrow + heading + optional lede, centred or left aligned. */
export function SectionHead({ eyebrow, title, body, align = 'center', pill = false, light = false }) {
  const centred = align === 'center'
  return (
    <div className={`${centred ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl'}`}>
      {eyebrow && (
        pill
          ? <span className="eyebrow-pill">{eyebrow}</span>
          : (
            <span className={`eyebrow ${light ? 'text-brand-200' : ''}`}>
              <span className={`h-px w-7 ${light ? 'bg-brand-200' : 'bg-brand-600'}`} aria-hidden="true" />
              {eyebrow}
            </span>
          )
      )}
      <h2 className={`mt-4 text-[30px] leading-[1.15] sm:text-[38px] ${light ? '!text-white' : ''}`}>
        {title}
      </h2>
      {body && (
        <p className={`mt-4 text-[17px] leading-relaxed ${light ? 'text-white/75' : 'text-muted'}`}>
          {body}
        </p>
      )}
    </div>
  )
}

/** A square tinted tile holding an icon — the Hostinger card motif. */
export function IconTile({ children, tone = 'brand' }) {
  const tones = {
    brand: 'bg-brand-50 text-brand-600',
    ink:   'bg-ink/5 text-ink',
    white: 'bg-white/10 text-white',
  }
  return (
    <div className={`flex h-12 w-12 items-center justify-center rounded-xl ${tones[tone]}`} aria-hidden="true">
      {children}
    </div>
  )
}

/**
 * FAQ accordion carried over from the Lovable service pages.
 *
 * Built on <details>/<summary> so it opens with keyboard alone, is readable
 * with JavaScript disabled, and is exposed to assistive tech without any ARIA
 * bookkeeping of our own.
 */
export function Faq({ items, title = 'What business leaders ask us.', eyebrow = 'Common questions' }) {
  const [openIdx, setOpenIdx] = useState(null)
  if (!items?.length) return null

  return (
    <section className="section bg-canvas">
      <div className="shell max-w-3xl">
        <SectionHead eyebrow={eyebrow} title={title} />
        <div className="mt-10 space-y-3">
          {items.map((item, i) => (
            <details
              key={item.q}
              open={openIdx === i}
              onToggle={(e) => {
                if (e.currentTarget.open) setOpenIdx(i)
                else if (openIdx === i) setOpenIdx(null)
              }}
              className="group overflow-hidden rounded-xl border border-line bg-white
                         transition-colors open:border-brand-200"
            >
              <summary
                className="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4
                           font-heading text-[15.5px] font-bold text-ink
                           marker:hidden hover:text-brand-600 [&::-webkit-details-marker]:hidden"
              >
                {item.q}
                <span className="shrink-0 text-brand-600" aria-hidden="true">
                  {openIdx === i ? <Minus size={17} /> : <Plus size={17} />}
                </span>
              </summary>
              <div className="px-5 pb-5 text-[15px] leading-relaxed text-muted">{item.a}</div>
            </details>
          ))}
        </div>
      </div>
    </section>
  )
}

/** The dark quote band used on the Lovable detail pages. */
export function Perspective({ eyebrow = 'TRG perspective', title, body }) {
  return (
    <section className="section">
      <div className="shell">
        <div className="relative overflow-hidden rounded-2xl bg-ink px-6 py-12 sm:px-12 sm:py-14">
          <div
            className="pointer-events-none absolute inset-0"
            style={{ background: 'radial-gradient(ellipse 60% 80% at 85% 20%, rgba(37,99,235,0.30) 0%, transparent 70%)' }}
            aria-hidden="true"
          />
          <div className="relative max-w-3xl">
            <span className="eyebrow text-brand-200">
              <span className="h-px w-7 bg-brand-200" aria-hidden="true" />
              {eyebrow}
            </span>
            <h2 className="mt-4 text-[26px] leading-[1.2] !text-white sm:text-[32px]">{title}</h2>
            <p className="mt-4 max-w-2xl text-[17px] leading-relaxed text-white/70">{body}</p>
          </div>
        </div>
      </div>
    </section>
  )
}

/** Closing call-to-action band. */
export function CtaBand({
  eyebrow = "Let's talk",
  title = 'Ready for technology that feels easier?',
  body = 'Start with a straightforward conversation about your business, your concerns and what better IT support could look like.',
}) {
  return (
    <section
      className="section relative overflow-hidden"
      style={{ background: 'linear-gradient(135deg, #0F172A 0%, #0D2247 55%, #1D4ED8 100%)' }}
    >
      <div className="shell relative grid items-center gap-10 lg:grid-cols-[1.4fr_1fr]">
        <SectionHead eyebrow={eyebrow} title={title} body={body} align="left" light />
        <div className="flex flex-col items-start gap-3 lg:items-end">
          <Link to="/contact" className="btn-white w-full sm:w-auto">
            Talk with our team <ArrowRight size={16} aria-hidden="true" />
          </Link>
          <a href={company.phoneHref} className="btn-ghost-l w-full sm:w-auto">
            Or call {company.phone}
          </a>
        </div>
      </div>
    </section>
  )
}

/** Feature grid used across every detail page. */
export function FeatureGrid({ items, columns = 3 }) {
  const cols = columns === 2 ? 'sm:grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3'
  return (
    <div className={`grid gap-5 ${cols}`}>
      {items.map((f) => (
        <div key={f.title} className="card-hover">
          <IconTile><Check size={20} /></IconTile>
          <h3 className="mt-4 text-[17px]">{f.title}</h3>
          <p className="mt-2 text-[15px] leading-relaxed text-muted">{f.body}</p>
        </div>
      ))}
    </div>
  )
}

/** Small rounded label, used for the Microsoft/Azure capability lists. */
export function Pills({ items }) {
  return (
    <ul className="flex flex-wrap gap-2">
      {items.map((p) => (
        <li
          key={p}
          className="rounded-full border border-brand-200 bg-brand-50 px-3.5 py-1.5
                     font-heading text-[13px] font-semibold text-brand-600"
        >
          {p}
        </li>
      ))}
    </ul>
  )
}

/** Standard inner-page hero. */
export function PageHero({ eyebrow, title, lede, children }) {
  return (
    <section className="relative overflow-hidden border-b border-line">
      <div
        className="pointer-events-none absolute inset-0"
        style={{ background: 'linear-gradient(160deg, #FFFFFF 0%, #F5F9FF 45%, #EFF6FF 100%)' }}
        aria-hidden="true"
      />
      <div className="shell relative py-14 sm:py-20">
        <div className="max-w-3xl">
          {eyebrow && <span className="eyebrow-pill">{eyebrow}</span>}
          <h1 className="mt-5 text-[34px] leading-[1.1] sm:text-[46px] lg:text-[52px]">{title}</h1>
          {lede && <p className="mt-5 max-w-2xl text-[18px] leading-relaxed text-muted">{lede}</p>}
          {children}
        </div>
      </div>
    </section>
  )
}

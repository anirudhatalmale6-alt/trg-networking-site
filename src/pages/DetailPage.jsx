import { Link, Navigate, useParams } from 'react-router-dom'
import { ArrowRight, Check } from 'lucide-react'
import Seo from '../components/Seo'
import {
  CtaBand, Faq, FeatureGrid, PageHero, Perspective, Pills, SectionHead,
} from '../components/Blocks'
import { detailPages } from '../data/detailPages'
import { company, industries, services } from '../data/site'

/**
 * One template renders all twelve service and industry detail pages, so a
 * section imported from the Lovable build looks purpose-built rather than
 * bolted on — the spacing, type scale and card treatment come from the same
 * place as the rest of the site.
 */
export default function DetailPage({ slug: fixedSlug }) {
  const params = useParams()
  const key = fixedSlug || params.slug
  const page = detailPages[key]

  if (!page) return <Navigate to="/404" replace />

  const siblings = page.kind === 'service'
    ? services.filter((s) => s.to !== page.slug).slice(0, 3)
    : industries.filter((i) => i.to !== page.slug && !i.anchor).slice(0, 3)

  return (
    <>
      <Seo title={page.metaTitle} description={page.metaDesc} image={page.image} />

      <PageHero eyebrow={page.eyebrow} title={page.title} lede={page.lede}>
        <div className="mt-8 flex flex-col gap-3 sm:flex-row">
          <Link to="/contact" className="btn-primary">
            Talk with our team <ArrowRight size={16} aria-hidden="true" />
          </Link>
          <a href={company.phoneHref} className="btn-outline">Call {company.phone}</a>
        </div>
      </PageHero>

      {/* intro + image */}
      <section className="section bg-white">
        <div className="shell grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
          <div>
            <SectionHead title={page.introTitle} body={page.introBody} align="left" />
            {page.pills && <div className="mt-7"><Pills items={page.pills} /></div>}
          </div>
          <img
            src={page.image}
            alt={page.imageAlt}
            width="1400" height="900"
            loading="lazy"
            className="aspect-[4/3] w-full rounded-2xl object-cover shadow-[0_24px_60px_-28px_rgba(15,23,42,0.4)]"
          />
        </div>
      </section>

      {/* capability grid */}
      <section className="section bg-canvas">
        <div className="shell">
          <SectionHead
            eyebrow={page.kind === 'service' ? 'What this includes' : 'How we help'}
            title={page.kind === 'service' ? 'What working with TRG looks like.' : 'Where TRG makes a difference.'}
          />
          <div className="mt-12">
            <FeatureGrid items={page.features} columns={page.features.length === 4 ? 2 : 3} />
          </div>
        </div>
      </section>

      <Perspective title={page.perspective.title} body={page.perspective.body} />

      {page.faq && <Faq items={page.faq} />}

      {/* related */}
      <section className="section bg-white">
        <div className="shell">
          <SectionHead
            eyebrow="Keep exploring"
            title={page.kind === 'service' ? 'Other ways TRG helps' : 'Other industries we serve'}
          />
          <div className="mt-10 grid gap-5 sm:grid-cols-3">
            {siblings.map((s) => (
              <Link key={s.to} to={s.to} className="card-hover group">
                <h3 className="text-[17px] group-hover:text-brand-600">{s.title}</h3>
                <p className="mt-2 text-[14.5px] leading-relaxed text-muted">{s.body}</p>
                <span className="mt-4 inline-flex items-center gap-1.5 font-heading text-[13px] font-bold text-brand-600">
                  Learn more
                  <ArrowRight size={13} className="transition-transform group-hover:translate-x-1" aria-hidden="true" />
                </span>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <CtaBand eyebrow={page.cta.eyebrow} title={page.cta.title} body={page.cta.body} />
    </>
  )
}

export { Check }

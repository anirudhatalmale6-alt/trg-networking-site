import { Link } from 'react-router-dom'
import { Linkedin, Mail, MapPin, Phone } from 'lucide-react'
import { addressLine, company, industriesNav, servicesNav } from '../data/site'

const companyLinks = [
  { label: 'About Us',   to: '/about' },
  { label: 'Why TRG',    to: '/why-trg' },
  { label: 'Services',   to: '/services' },
  { label: 'Industries', to: '/industries' },
  { label: 'Contact',    to: '/contact' },
]

const resourceLinks = [
  { label: 'Resources & Insights', to: '/resources' },
  { label: 'Case Studies',         to: '/resources/case-studies' },
  { label: 'Guides & Downloads',   to: '/resources/guides' },
]

function Column({ title, links }) {
  return (
    <div>
      <h4 className="mb-4 font-heading text-[12px] font-bold uppercase tracking-[0.14em] text-white">
        {title}
      </h4>
      <ul className="space-y-2.5">
        {links.map((l) => (
          <li key={l.to + l.label}>
            <Link to={l.to} className="text-[14px] text-white/65 transition-colors hover:text-white">
              {l.label}
            </Link>
          </li>
        ))}
      </ul>
    </div>
  )
}

export default function Footer() {
  const year = new Date().getFullYear()

  return (
    <footer className="bg-ink pt-16 pb-8 text-white">
      <div className="shell">
        <div className="grid gap-10 lg:grid-cols-[1.6fr_1fr_1fr_1fr_1.3fr]">
          <div>
            <img
              src="/img/logo-footer.webp"
              alt="TRG Networking"
              width="600" height="264"
              className="mb-5 h-11 w-auto"
            />
            <p className="max-w-xs text-[14px] leading-relaxed text-white/65">
              {company.blurb}
            </p>
            {/* Only real, working profiles are listed. The previous build had
                four placeholder icons that all pointed at "#". */}
            <a
              href={company.linkedin}
              target="_blank"
              rel="noopener noreferrer"
              aria-label="TRG Networking on LinkedIn"
              className="mt-5 inline-flex h-10 w-10 items-center justify-center rounded-lg
                         border border-white/15 bg-white/5 text-white/80
                         transition-colors hover:border-brand-400 hover:bg-brand-600 hover:text-white"
            >
              <Linkedin size={17} aria-hidden="true" />
            </a>
          </div>

          <Column title="Services"   links={servicesNav} />
          <Column title="Industries" links={industriesNav} />
          <Column title="Company"    links={[...companyLinks, ...resourceLinks]} />

          <div>
            <h4 className="mb-4 font-heading text-[12px] font-bold uppercase tracking-[0.14em] text-white">
              Contact
            </h4>
            <ul className="space-y-3 text-[14px] text-white/65">
              <li>
                <a href={company.phoneHref} className="flex items-start gap-2.5 hover:text-white">
                  <Phone size={15} className="mt-0.5 shrink-0 text-brand-400" aria-hidden="true" />
                  {company.phone}
                </a>
              </li>
              <li>
                <a href={`mailto:${company.email}`} className="flex items-start gap-2.5 break-all hover:text-white">
                  <Mail size={15} className="mt-0.5 shrink-0 text-brand-400" aria-hidden="true" />
                  {company.email}
                </a>
              </li>
              <li className="flex items-start gap-2.5">
                <MapPin size={15} className="mt-0.5 shrink-0 text-brand-400" aria-hidden="true" />
                <span>{addressLine}</span>
              </li>
            </ul>
            <a
              href={company.supportUrl}
              target="_blank"
              rel="noopener noreferrer"
              className="btn-ghost-l mt-5 w-full"
            >
              Existing client support
            </a>
          </div>
        </div>

        <div className="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 sm:flex-row">
          <p className="text-[13px] text-white/50">
            © {year} {company.legalName} All rights reserved. · Women / Minority Owned
          </p>
          <ul className="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-[13px] text-white/50">
            <li><Link to="/privacy" className="hover:text-white">Privacy</Link></li>
            <li><Link to="/terms" className="hover:text-white">Terms</Link></li>
            <li><Link to="/accessibility" className="hover:text-white">Accessibility</Link></li>
          </ul>
        </div>
      </div>
    </footer>
  )
}

import { useEffect, useRef, useState } from 'react'
import { Link, NavLink, useLocation } from 'react-router-dom'
import { ChevronDown, Menu, Phone, X } from 'lucide-react'
import { company, mainNav } from '../data/site'

/**
 * Header = Hostinger's white sticky bar and blue CTA button, plus the utility
 * bar and the "Existing Client Support" link that only the Lovable build had.
 */
export default function Header() {
  const [open, setOpen] = useState(false)          // mobile drawer
  const [openMenu, setOpenMenu] = useState(null)   // desktop dropdown label
  const [scrolled, setScrolled] = useState(false)
  const { pathname, hash } = useLocation()
  const navRef = useRef(null)

  // Close everything on navigation.
  useEffect(() => { setOpen(false); setOpenMenu(null) }, [pathname, hash])

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8)
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  // A dropdown left open after the pointer moves away is a trap on touch
  // devices — close on outside click and on Escape.
  useEffect(() => {
    const onDown = (e) => {
      if (navRef.current && !navRef.current.contains(e.target)) setOpenMenu(null)
    }
    const onKey = (e) => {
      if (e.key === 'Escape') { setOpenMenu(null); setOpen(false) }
    }
    document.addEventListener('mousedown', onDown)
    document.addEventListener('keydown', onKey)
    return () => {
      document.removeEventListener('mousedown', onDown)
      document.removeEventListener('keydown', onKey)
    }
  }, [])

  // Lock body scroll while the mobile drawer is open.
  useEffect(() => {
    document.body.style.overflow = open ? 'hidden' : ''
    return () => { document.body.style.overflow = '' }
  }, [open])

  return (
    <>
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:absolute focus:z-[100] focus:left-4 focus:top-4
                   focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm
                   focus:font-semibold focus:text-white"
      >
        Skip to content
      </a>

      {/* Utility bar — lifted from the Lovable build. */}
      <div className="hidden bg-ink text-white md:block">
        <div className="shell flex h-9 items-center justify-between text-[12.5px]">
          <p className="text-white/70">{company.tagline}</p>
          <div className="flex items-center gap-5">
            <a href={company.phoneHref} className="flex items-center gap-1.5 font-heading font-semibold hover:text-brand-200">
              <Phone size={13} aria-hidden="true" />
              {company.phone}
            </a>
            <a
              href={company.supportUrl}
              target="_blank"
              rel="noopener noreferrer"
              className="font-heading font-semibold text-white/85 hover:text-brand-200"
            >
              Existing Client Support
            </a>
          </div>
        </div>
      </div>

      <header
        className={`sticky top-0 z-50 bg-white transition-shadow duration-200 ${
          scrolled ? 'shadow-[0_1px_0_0_#E2E8F0,0_8px_24px_-16px_rgba(15,23,42,0.25)]' : 'border-b border-line'
        }`}
      >
        <div className="shell flex h-[68px] items-center justify-between gap-4">
          <Link to="/" className="flex shrink-0 items-center" aria-label={`${company.name} — home`}>
            <img
              src="/img/logo-trg.webp"
              alt="TRG Networking"
              width="587" height="216"
              className="h-9 w-auto sm:h-10"
            />
          </Link>

          {/* min-w-0 stops the nav growing past the header and pushing the CTA
              off-screen at awkward widths. */}
          <nav ref={navRef} className="hidden min-w-0 flex-1 items-center justify-center lg:flex" aria-label="Main">
            <ul className="flex items-center gap-0.5">
              {mainNav.map((item) => (
                <li key={item.label} className="relative">
                  {item.children ? (
                    <>
                      <button
                        type="button"
                        onClick={() => setOpenMenu(openMenu === item.label ? null : item.label)}
                        aria-expanded={openMenu === item.label}
                        className={`flex items-center gap-1 rounded-md px-3 py-2 font-heading text-[14.5px] font-semibold transition-colors ${
                          pathname.startsWith(item.to) ? 'text-brand-600' : 'text-body hover:text-brand-600'
                        }`}
                      >
                        {item.label}
                        <ChevronDown
                          size={14}
                          aria-hidden="true"
                          className={`transition-transform ${openMenu === item.label ? 'rotate-180' : ''}`}
                        />
                      </button>
                      {openMenu === item.label && (
                        <div
                          className="absolute left-1/2 top-full z-50 mt-1 w-[290px] -translate-x-1/2 rounded-xl
                                     border border-line bg-white p-2 shadow-[0_18px_44px_-16px_rgba(15,23,42,0.3)]"
                        >
                          <Link
                            to={item.to}
                            className="block rounded-lg px-3 py-2 font-heading text-[13px] font-bold uppercase
                                       tracking-wider text-brand-600 hover:bg-brand-50"
                          >
                            All {item.label}
                          </Link>
                          <div className="my-1 h-px bg-line" />
                          {item.children.map((c) => (
                            <Link
                              key={c.to}
                              to={c.to}
                              className="block rounded-lg px-3 py-2 text-[14px] text-body hover:bg-brand-50 hover:text-brand-600"
                            >
                              {c.label}
                            </Link>
                          ))}
                        </div>
                      )}
                    </>
                  ) : (
                    <NavLink
                      to={item.to}
                      className={({ isActive }) =>
                        `block rounded-md px-3 py-2 font-heading text-[14.5px] font-semibold transition-colors ${
                          isActive ? 'text-brand-600' : 'text-body hover:text-brand-600'
                        }`
                      }
                    >
                      {item.label}
                    </NavLink>
                  )}
                </li>
              ))}
            </ul>
          </nav>

          <div className="flex shrink-0 items-center gap-2">
            <Link to="/contact" className="btn-primary hidden sm:inline-flex">
              Talk with our team
            </Link>
            <button
              type="button"
              onClick={() => setOpen(true)}
              className="rounded-lg border border-line p-2 text-ink lg:hidden"
              aria-label="Open menu"
              aria-expanded={open}
            >
              <Menu size={20} aria-hidden="true" />
            </button>
          </div>
        </div>
      </header>

      {/* Mobile drawer */}
      {open && (
        <div className="fixed inset-0 z-[60] lg:hidden">
          <div
            className="absolute inset-0 bg-ink/45"
            onClick={() => setOpen(false)}
            aria-hidden="true"
          />
          <div className="absolute right-0 top-0 flex h-full w-[88%] max-w-sm flex-col bg-white shadow-2xl">
            <div className="flex h-[68px] shrink-0 items-center justify-between border-b border-line px-5">
              <img src="/img/logo-trg.webp" alt="TRG Networking" className="h-9 w-auto" />
              <button
                type="button"
                onClick={() => setOpen(false)}
                className="rounded-lg border border-line p-2 text-ink"
                aria-label="Close menu"
              >
                <X size={20} aria-hidden="true" />
              </button>
            </div>

            <nav className="flex-1 overflow-y-auto px-5 py-5" aria-label="Mobile">
              <ul className="space-y-1">
                {mainNav.map((item) => (
                  <li key={item.label}>
                    <Link
                      to={item.to}
                      className="block rounded-lg px-3 py-2.5 font-heading text-[15px] font-bold text-ink hover:bg-brand-50"
                    >
                      {item.label}
                    </Link>
                    {item.children && (
                      <ul className="mb-2 ml-3 border-l border-line pl-3">
                        {item.children.map((c) => (
                          <li key={c.to}>
                            <Link
                              to={c.to}
                              className="block rounded-lg px-3 py-2 text-[14px] text-muted hover:bg-brand-50 hover:text-brand-600"
                            >
                              {c.label}
                            </Link>
                          </li>
                        ))}
                      </ul>
                    )}
                  </li>
                ))}
              </ul>

              <div className="mt-6 space-y-3 border-t border-line pt-6">
                <Link to="/contact" className="btn-primary w-full">Talk with our team</Link>
                <a href={company.phoneHref} className="btn-outline w-full">
                  <Phone size={15} aria-hidden="true" /> {company.phone}
                </a>
                <a
                  href={company.supportUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="block px-1 pt-1 text-center text-sm font-semibold text-muted hover:text-brand-600"
                >
                  Existing Client Support
                </a>
              </div>
            </nav>
          </div>
        </div>
      )}
    </>
  )
}

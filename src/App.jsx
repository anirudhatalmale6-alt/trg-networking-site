import { useEffect } from 'react'
import { Navigate, Route, Routes, useLocation } from 'react-router-dom'
import Header from './components/Header'
import Footer from './components/Footer'
import Home from './pages/Home'
import DetailPage from './pages/DetailPage'
import Contact, { Resources } from './pages/Contact'
import { About, Industries, Services, WhyTrg } from './pages/Simple'
import { Accessibility, CaseStudies, Guides, NotFound, Privacy, Terms } from './pages/Misc'
import { legacyRedirects } from './data/detailPages'

/**
 * Client routing keeps the scroll position of the previous page, which makes a
 * new page look like it loaded halfway down. Reset it — unless the URL carries
 * a hash, in which case let the browser scroll to that element.
 */
function ScrollToTop() {
  const { pathname, hash } = useLocation()
  useEffect(() => {
    if (hash) {
      const el = document.querySelector(hash)
      if (el) { el.scrollIntoView({ behavior: 'smooth' }); return }
    }
    window.scrollTo({ top: 0, left: 0 })
  }, [pathname, hash])
  return null
}

// Lovable's URL structure: service pages and industry pages both sit at the root.
const DETAIL_SLUGS = [
  'managed-it-services', 'help-desk-it-support', 'cybersecurity',
  'microsoft-365-cloud', 'azure', 'secure-ai-adoption', 'cmmc-readiness',
  'backup-business-continuity',
  'construction', 'manufacturing', 'government-contractors', 'professional-services',
]

export default function App() {
  return (
    <>
      <ScrollToTop />
      <Header />
      <main id="main-content">
        <Routes>
          <Route path="/" element={<Home />} />

          <Route path="/services"   element={<Services />} />
          <Route path="/industries" element={<Industries />} />
          <Route path="/why-trg"    element={<WhyTrg />} />
          <Route path="/about"      element={<About />} />
          <Route path="/contact"    element={<Contact />} />

          <Route path="/resources"              element={<Resources />} />
          <Route path="/resources/case-studies" element={<CaseStudies />} />
          <Route path="/resources/guides"       element={<Guides />} />

          {DETAIL_SLUGS.map((s) => (
            <Route key={s} path={`/${s}`} element={<DetailPage slug={s} />} />
          ))}

          {/* Interim Hostinger-build slugs. The .htaccess issues a real 301 on a
              direct hit; this handles in-app navigation to the same paths. */}
          {Object.entries(legacyRedirects).map(([from, to]) => (
            <Route key={from} path={from} element={<Navigate to={to} replace />} />
          ))}

          <Route path="/privacy"       element={<Privacy />} />
          <Route path="/terms"         element={<Terms />} />
          <Route path="/accessibility" element={<Accessibility />} />

          <Route path="*" element={<NotFound />} />
        </Routes>
      </main>
      <Footer />
    </>
  )
}

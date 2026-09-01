/**
 * Pre-render every route to a real HTML file.
 *
 * The site is a client-rendered React app, so the HTML the server sends is an
 * empty <div id="root">. Any crawler or link-preview bot that does not run
 * JavaScript sees a blank page — which is what the previous build shipped.
 *
 * This walks the built site in a real browser, waits for React to render, and
 * writes the resulting HTML to dist/<route>/index.html. The .htaccess serves
 * those files directly, so a visitor (and Google, and LinkedIn) gets real
 * markup on the first byte. React then hydrates on top as usual.
 *
 * Run: node scripts/prerender.mjs   (npm run build does this automatically)
 */
import { chromium } from 'playwright'
import { mkdir, writeFile } from 'node:fs/promises'
import { dirname, join } from 'node:path'
import { preview } from 'vite'

const ROUTES = [
  '/',
  '/services', '/industries', '/why-trg', '/about', '/contact',
  '/resources', '/resources/case-studies', '/resources/guides',
  '/managed-it-services', '/help-desk-it-support', '/cybersecurity',
  '/microsoft-365-cloud', '/azure', '/secure-ai-adoption', '/cmmc-readiness',
  '/backup-business-continuity',
  '/construction', '/manufacturing', '/government-contractors',
  '/professional-services',
  '/privacy', '/terms', '/accessibility',
]

const SITE = process.env.SITE_URL || 'https://www.trgnetworking.com'
const PORT = 4319

const server = await preview({
  preview: { port: PORT, strictPort: true },
  logLevel: 'silent',
})

// Prefer the browser Playwright manages. If none is installed, fall back to a
// system Chrome via CHROME_PATH. If neither exists, skip pre-rendering with a
// loud warning rather than failing the build — the site still works, it just
// serves crawlers the empty shell until this runs on a machine that has one.
let browser
try {
  browser = await chromium.launch(
    process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {}
  )
} catch (err) {
  console.warn('\n  !! Pre-render SKIPPED — no browser available.')
  console.warn('     Run "npx playwright install chromium", or set CHROME_PATH')
  console.warn('     to a Chrome/Chromium binary, then run "npm run build" again.')
  console.warn(`     (${err.message.split('\n')[0]})\n`)
  await server.close()
  process.exit(0)
}

const page = await browser.newPage({ viewport: { width: 1280, height: 900 } })

let written = 0
for (const route of ROUTES) {
  await page.goto(`http://localhost:${PORT}${route}`, { waitUntil: 'networkidle' })
  await page.waitForSelector('#root > *', { timeout: 15000 })
  // Give <Seo> its effect tick so the title/description/canonical are in the DOM.
  await page.waitForTimeout(250)

  let html = await page.content()

  // <Seo> builds canonical and og:url from window.location.origin, which
  // during pre-render is the local preview server. Rewrite those to the real
  // site — shipping "http://localhost:4319/..." as a canonical would tell
  // Google the page lives on a machine it cannot reach.
  html = html.replaceAll(`http://localhost:${PORT}`, SITE)

  const outPath = route === '/'
    ? 'dist/index.html'
    : join('dist', route.replace(/^\//, ''), 'index.html')

  await mkdir(dirname(outPath), { recursive: true })
  await writeFile(outPath, html, 'utf8')
  written++
  console.log(`  prerendered ${route}`)
}

// --- sitemap --------------------------------------------------------------
const today = new Date().toISOString().slice(0, 10)
const sitemap =
  `<?xml version="1.0" encoding="UTF-8"?>\n` +
  `<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n` +
  ROUTES.map((r) => {
    const priority = r === '/' ? '1.0' : r.split('/').length > 2 ? '0.6' : '0.8'
    return `  <url>\n    <loc>${SITE}${r}</loc>\n    <lastmod>${today}</lastmod>\n` +
           `    <changefreq>monthly</changefreq>\n    <priority>${priority}</priority>\n  </url>`
  }).join('\n') +
  `\n</urlset>\n`
await writeFile('dist/sitemap.xml', sitemap, 'utf8')

await browser.close()
await server.close()
console.log(`\n  ${written} routes prerendered, sitemap.xml written (${SITE})`)

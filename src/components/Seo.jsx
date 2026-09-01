import { useEffect } from 'react'
import { useLocation } from 'react-router-dom'

function upsertMeta(attr, key, content) {
  if (!content) return
  let el = document.head.querySelector(`meta[${attr}="${key}"]`)
  if (!el) {
    el = document.createElement('meta')
    el.setAttribute(attr, key)
    document.head.appendChild(el)
  }
  el.setAttribute('content', content)
}

/**
 * Per-page title, description and canonical URL.
 *
 * This is a client-rendered app, so crawlers that do not execute JavaScript
 * only ever see index.html. The build step (scripts/prerender.mjs) writes a
 * real static HTML file per route so the tags below are also present in the
 * served source.
 */
export default function Seo({ title, description, image }) {
  const { pathname } = useLocation()

  useEffect(() => {
    if (title) document.title = title
    upsertMeta('name', 'description', description)
    upsertMeta('property', 'og:title', title)
    upsertMeta('property', 'og:description', description)
    upsertMeta('property', 'og:url', window.location.origin + pathname)
    if (image) upsertMeta('property', 'og:image', window.location.origin + image)

    let link = document.head.querySelector('link[rel="canonical"]')
    if (!link) {
      link = document.createElement('link')
      link.setAttribute('rel', 'canonical')
      document.head.appendChild(link)
    }
    link.setAttribute('href', window.location.origin + pathname)
  }, [title, description, image, pathname])

  return null
}

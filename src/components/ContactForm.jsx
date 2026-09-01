import { useState } from 'react'
import { AlertCircle, CheckCircle2, Loader2, Send } from 'lucide-react'
import { company, serviceOptions } from '../data/site'

/**
 * The enquiry form. This is the one genuinely interactive widget on either
 * build — the Lovable version had no form at all, only a mailto link.
 *
 * Where submissions go
 * --------------------
 * The previous Hostinger build wrote straight into a hidden Horizons database
 * collection ("consultation_requests") and emailed nobody, so enquiries could
 * pile up unread behind a success message. This version posts to ENDPOINT,
 * which is a small PHP handler (public/api/contact.php) that mails NOTIFY_TO
 * and sets Reply-To to the enquirer.
 *
 * If that POST fails for any reason the form does NOT show a false success.
 * It surfaces the error and offers a mailto fallback with the message already
 * written out, so a lead is never silently dropped.
 */
const ENDPOINT = '/api/contact.php'

const EMPTY = {
  name: '', email: '', company: '', phone: '',
  service: '', type: 'consultation', message: '',
}

export default function ContactForm() {
  const [form, setForm] = useState(EMPTY)
  const [status, setStatus] = useState('idle') // idle | loading | success | error
  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }))

  const mailtoFallback = () => {
    const body = [
      `Name: ${form.name}`,
      `Email: ${form.email}`,
      `Company: ${form.company}`,
      `Phone: ${form.phone}`,
      `Service of interest: ${form.service}`,
      `Request type: ${form.type}`,
      '',
      form.message,
    ].join('\n')
    return `mailto:${company.email}?subject=${encodeURIComponent(
      form.type === 'assessment' ? 'Free IT Assessment request' : 'Consultation request'
    )}&body=${encodeURIComponent(body)}`
  }

  const onSubmit = async (e) => {
    e.preventDefault()
    setStatus('loading')
    try {
      const res = await fetch(ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(form),
      })
      // A 200 that is not JSON usually means the host served an HTML error
      // page instead of running the handler — treat that as a failure, not a
      // success, so we never tell a visitor their message was sent when it
      // was not.
      const data = await res.json().catch(() => null)
      if (!res.ok || !data?.ok) throw new Error(data?.error || `HTTP ${res.status}`)
      setStatus('success')
      setForm(EMPTY)
    } catch {
      setStatus('error')
    }
  }

  const field =
    'w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] text-body ' +
    'transition-all placeholder:text-soft/70 focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200'
  const label = 'mb-1.5 block font-heading text-[11px] font-bold uppercase tracking-[0.12em] text-ink'

  if (status === 'success') {
    return (
      <div className="rounded-2xl border border-line bg-white p-8 text-center shadow-sm">
        <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-600">
          <CheckCircle2 size={28} aria-hidden="true" />
        </div>
        <h3 className="mt-5 text-[20px]">Message sent</h3>
        <p className="mx-auto mt-2 max-w-sm text-[15px] leading-relaxed text-muted">
          Thank you for contacting {company.name}. A member of our team will be in touch shortly.
          If it is urgent, call{' '}
          <a href={company.phoneHref} className="font-semibold text-brand-600">{company.phone}</a>.
        </p>
        <button type="button" onClick={() => setStatus('idle')} className="btn-outline mt-6">
          Send another message
        </button>
      </div>
    )
  }

  return (
    <form
      onSubmit={onSubmit}
      className="space-y-4 rounded-2xl border border-line bg-white p-6 shadow-sm sm:p-7"
      noValidate={false}
    >
      <fieldset>
        <legend className={label}>What would you like to do?</legend>
        <div className="grid grid-cols-2 gap-3">
          {[
            { v: 'consultation', label: 'Schedule a consultation' },
            { v: 'assessment',   label: 'Free IT assessment' },
          ].map((o) => (
            <button
              key={o.v}
              type="button"
              aria-pressed={form.type === o.v}
              onClick={() => setForm((f) => ({ ...f, type: o.v }))}
              className={`rounded-lg border px-4 py-2.5 font-heading text-[13.5px] font-semibold transition-all ${
                form.type === o.v
                  ? 'border-brand-600 bg-brand-600 text-white'
                  : 'border-line bg-white text-muted hover:border-brand-200 hover:text-brand-600'
              }`}
            >
              {o.label}
            </button>
          ))}
        </div>
      </fieldset>

      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label className={label} htmlFor="cf-name">Full name *</label>
          <input id="cf-name" name="name" required autoComplete="name"
                 value={form.name} onChange={set('name')} className={field} placeholder="Jane Smith" />
        </div>
        <div>
          <label className={label} htmlFor="cf-email">Email address *</label>
          <input id="cf-email" name="email" type="email" required autoComplete="email"
                 value={form.email} onChange={set('email')} className={field} placeholder="jane@company.com" />
        </div>
      </div>

      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label className={label} htmlFor="cf-company">Company</label>
          <input id="cf-company" name="company" autoComplete="organization"
                 value={form.company} onChange={set('company')} className={field} placeholder="Your organization" />
        </div>
        <div>
          <label className={label} htmlFor="cf-phone">Phone</label>
          <input id="cf-phone" name="phone" type="tel" autoComplete="tel"
                 value={form.phone} onChange={set('phone')} className={field} placeholder="(301) 555-0000" />
        </div>
      </div>

      <div>
        <label className={label} htmlFor="cf-service">Service of interest</label>
        <select id="cf-service" name="service" value={form.service} onChange={set('service')} className={field}>
          <option value="">Select a service…</option>
          {serviceOptions.map((o) => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
      </div>

      <div>
        <label className={label} htmlFor="cf-message">Message</label>
        <textarea id="cf-message" name="message" rows={4} value={form.message} onChange={set('message')}
                  className={`${field} resize-y`} placeholder="Tell us about your IT needs and goals…" />
      </div>

      {/* Honeypot — bots fill it, people never see it. */}
      <input
        type="text" name="website" tabIndex={-1} autoComplete="off"
        value={form.website || ''} onChange={set('website')}
        className="absolute left-[-9999px] h-0 w-0 opacity-0" aria-hidden="true"
      />

      {status === 'error' && (
        <p
          role="alert"
          className="flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-[14px] text-red-700"
        >
          <AlertCircle size={17} className="mt-0.5 shrink-0" aria-hidden="true" />
          <span>
            We could not send that from the website. Please{' '}
            <a href={mailtoFallback()} className="font-semibold underline">email it to us instead</a>{' '}
            or call <a href={company.phoneHref} className="font-semibold underline">{company.phone}</a>.
          </span>
        </p>
      )}

      <button type="submit" disabled={status === 'loading'} className="btn-primary w-full disabled:opacity-70">
        {status === 'loading'
          ? <><Loader2 size={16} className="animate-spin" aria-hidden="true" /> Sending…</>
          : <><Send size={15} aria-hidden="true" /> Send message</>}
      </button>

      <p className="text-center text-[12.5px] text-soft">
        We use your details only to respond to this enquiry.
      </p>
    </form>
  )
}

// Single source of truth for everything that repeats across the site.
// Editing a phone number, an address or a nav item here changes it everywhere.

export const company = {
  name: 'TRG Networking',
  legalName: 'TRG Networking, Inc.',
  founded: 1992,
  phone: '410-363-6980',
  phoneHref: 'tel:+14103636980',
  email: 'info@trgnetworking.com',
  marketingEmail: 'marketing@trgnetworking.com',
  supportUrl: 'https://www.trgnetworking.com/support-center/',
  linkedin: 'https://www.linkedin.com/company/trg-networking-inc',
  address: {
    street: '9861 Broken Land Parkway, Suite 100',
    city: 'Columbia',
    state: 'Maryland',
    stateShort: 'MD',
    zip: '21046',
  },
  tagline: 'Maryland-based • Supporting businesses nationwide',
  blurb:
    'Making technology simpler, safer and more responsive for businesses since 1992.',
}

export const addressLine = `${company.address.street}, ${company.address.city}, ${company.address.state} ${company.address.zip}`

// ---------------------------------------------------------------------------
// Navigation
// ---------------------------------------------------------------------------

export const servicesNav = [
  { label: 'Managed IT Services',      to: '/managed-it-services' },
  { label: 'Help Desk & IT Support',   to: '/help-desk-it-support' },
  { label: 'Cybersecurity',            to: '/cybersecurity' },
  { label: 'Microsoft 365 & Cloud',    to: '/microsoft-365-cloud' },
  { label: 'Azure Cloud',              to: '/azure' },
  { label: 'Secure AI Adoption',       to: '/secure-ai-adoption' },
  { label: 'CMMC Readiness',           to: '/cmmc-readiness' },
  { label: 'Backup & Business Continuity', to: '/backup-business-continuity' },
]

export const industriesNav = [
  { label: 'Construction & Contractors', to: '/construction' },
  { label: 'Manufacturing',              to: '/manufacturing' },
  { label: 'Government Contractors',     to: '/government-contractors' },
  { label: 'Professional Services',      to: '/professional-services' },
  { label: 'Healthcare',                 to: '/industries#healthcare' },
  { label: 'Nonprofits',                 to: '/industries#nonprofits' },
]

export const mainNav = [
  { label: 'Services',   to: '/services',   children: servicesNav },
  { label: 'Industries', to: '/industries', children: industriesNav },
  { label: 'Why TRG',    to: '/why-trg' },
  { label: 'Resources',  to: '/resources' },
  { label: 'About',      to: '/about' },
]

// ---------------------------------------------------------------------------
// Homepage building blocks
// ---------------------------------------------------------------------------

export const heroBadges = [
  'Microsoft Partner',
  '24×7 Support',
  'CMMC Readiness',
  'Women/Minority Owned',
]

export const heroFloatCards = [
  { eyebrow: 'Responsive support', title: 'Real people. Clear ownership.' },
  { eyebrow: 'Security first',     title: 'Protection built in.' },
]

// Figures the client can verify. "Since 1992" is used instead of a hard-coded
// year count so the number can never drift out of date.
export const stats = [
  { value: '1992',  label: 'Serving clients since' },
  { value: '163+',  label: 'Organizations served' },
  { value: '8',     label: 'Technology solution areas' },
  { value: '24×7',  label: 'Monitoring & support' },
]

export const partners = [
  'Microsoft', 'Cisco', 'Dell', 'Fortinet',
  'Sophos', 'Datto', 'Synnex', 'ConnectWise',
]

export const trustStrip = [
  'Managed IT', 'Cybersecurity', 'Microsoft 365', 'CMMC', 'Secure AI',
]

// The four outcome cards from the Lovable build.
export const outcomes = [
  {
    title: 'Fewer interruptions',
    body: 'Proactive monitoring and maintenance help address small issues before they become costly problems.',
    icon: 'activity',
  },
  {
    title: 'Stronger protection',
    body: 'Security that covers people, devices, cloud systems, data and the everyday decisions that connect them.',
    icon: 'shield',
  },
  {
    title: 'Responsive attention',
    body: 'Multiple team members oversee incoming support so requests are seen, assigned and kept moving.',
    icon: 'users',
  },
  {
    title: 'Clearer planning',
    body: 'Plain-English recommendations, predictable costs and a practical roadmap for what comes next.',
    icon: 'map',
  },
]

// Service cards — Lovable's copy and Lovable's URL structure, plus the services
// that only existed on the Hostinger build (Azure Cloud).
export const services = [
  {
    to: '/managed-it-services',
    title: 'Managed IT Services',
    body: 'Proactive care, responsive support and a clear technology plan — without the cost of building an internal IT department.',
    icon: 'server',
  },
  {
    to: '/cybersecurity',
    title: 'Cybersecurity',
    body: 'Layered protection for your people, devices, identities and data, backed by practical guidance your team can follow.',
    icon: 'shield',
  },
  {
    to: '/microsoft-365-cloud',
    title: 'Microsoft 365 & Cloud',
    body: 'Licensing, security, migrations and everyday support that help your organization get more from Microsoft 365.',
    icon: 'cloud',
  },
  {
    to: '/secure-ai-adoption',
    title: 'Secure AI Adoption',
    body: 'Policies, training and practical use cases that help your team save time with AI while protecting company information.',
    icon: 'sparkles',
  },
  {
    to: '/backup-business-continuity',
    title: 'Backup & Business Continuity',
    body: 'Verified backups and recovery planning designed to keep a disruption from becoming a business-ending event.',
    icon: 'database',
  },
  {
    to: '/cmmc-readiness',
    title: 'CMMC Readiness',
    body: 'Technology and security guidance for government contractors working toward a stronger, audit-ready environment.',
    icon: 'badge',
  },
  {
    to: '/help-desk-it-support',
    title: 'Help Desk & IT Support',
    body: 'Friendly, responsive support that treats people with respect and keeps issues moving toward resolution.',
    icon: 'headset',
  },
  {
    to: '/azure',
    title: 'Azure Cloud',
    body: 'Azure architecture, migration and optimization — designed for performance, cost control and security.',
    icon: 'cloudcog',
  },
]

// Numbered industry list from Lovable, extended with the two industries that
// only appeared on the Hostinger build.
export const industries = [
  {
    n: '01',
    to: '/construction',
    title: 'Construction & Contractors',
    body: 'Keep field teams, offices and projects connected without technology slowing the work.',
    tags: 'Field connectivity, BEC protection',
  },
  {
    n: '02',
    to: '/manufacturing',
    title: 'Manufacturing',
    body: 'Protect production, reduce disruption and build a technology foundation that supports growth.',
    tags: 'OT security, ERP, production uptime',
  },
  {
    n: '03',
    to: '/government-contractors',
    title: 'Government Contractors',
    body: 'Strengthen security practices and move toward CMMC readiness with experienced guidance.',
    tags: 'CMMC, NIST 800-171, CUI protection',
  },
  {
    n: '04',
    to: '/professional-services',
    title: 'Professional Services',
    body: 'Give your people secure, reliable tools to serve clients from the office or anywhere else.',
    tags: 'Law firms, CPAs, consultancies',
  },
  {
    n: '05',
    to: '/industries#healthcare',
    anchor: 'healthcare',
    title: 'Healthcare',
    body: 'Protect patient information and keep clinical systems dependable for the people who rely on them.',
    tags: 'HIPAA compliance, EHR, clinical IT',
  },
  {
    n: '06',
    to: '/industries#nonprofits',
    anchor: 'nonprofits',
    title: 'Nonprofits',
    body: 'Affordable, mission-aligned technology that stretches limited budgets without cutting corners on security.',
    tags: 'Affordable mission-aligned IT',
  },
]

// Attributed testimonials carried over from the Lovable build.
export const testimonials = [
  {
    quote: 'TRG gives that personal touch, good communication and a skilled staff that fully understands our needs.',
    name: 'Nick Pirovolidis',
    org: 'BSC America',
  },
  {
    quote: 'TRG personnel provide prompt and thorough support as well as cost-effective management of our IT needs.',
    name: 'Todd Hirsch',
    org: 'Belt Built Contracting, LLC',
  },
]

export const aiSteps = [
  { n: '01', title: 'Protect the data',      sub: 'Security and access first' },
  { n: '02', title: 'Set clear policies',    sub: 'Responsible use guidance' },
  { n: '03', title: 'Find practical wins',   sub: 'Workflows worth improving' },
  { n: '04', title: 'Train the team',        sub: 'Confidence without the hype' },
]

export const aiTags = ['AI readiness', 'Usage policies', 'Microsoft Copilot', 'Employee training']

export const processSteps = [
  { n: 1, title: 'Talk with our team',      body: 'We listen first and learn what your organization needs.' },
  { n: 2, title: 'Review your environment', body: 'We identify risks, gaps and opportunities worth addressing.' },
  { n: 3, title: 'Build a practical plan',  body: 'You receive clear priorities and a sensible path forward.' },
]

export const whyTrgPoints = [
  { title: 'Enterprise experience',  body: 'Deep expertise with complex environments and compliance-driven organizations.' },
  { title: 'Strategic IT leadership', body: 'vCIO services that align technology investments with your business objectives.' },
  { title: 'Microsoft experts',       body: 'Certified Microsoft Partner delivering Azure, 365, Teams and Copilot AI.' },
  { title: 'Security-first design',   body: 'Cybersecurity is built into every solution — not bolted on as an afterthought.' },
  { title: 'CMMC & compliance',       body: 'A track record of helping defense contractors work toward and maintain compliance.' },
  { title: 'AI-ready organization',   body: 'Helping teams responsibly adopt AI tools for measurable business impact.' },
]

export const serviceOptions = [
  { value: 'managed-it',          label: 'Managed IT Services' },
  { value: 'help-desk',           label: 'Help Desk & IT Support' },
  { value: 'cybersecurity',       label: 'Cybersecurity' },
  { value: 'microsoft',           label: 'Microsoft Solutions (Azure / 365)' },
  { value: 'cmmc',                label: 'CMMC Readiness' },
  { value: 'ai',                  label: 'Secure AI Adoption' },
  { value: 'business-continuity', label: 'Backup & Business Continuity' },
  { value: 'other',               label: 'Other / General Inquiry' },
]

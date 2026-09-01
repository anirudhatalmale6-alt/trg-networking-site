/** @type {import('tailwindcss').Config} */
// Same design tokens as the React build, so the WordPress site is the same site
// rather than a lookalike. Changing `brand` here re-themes everything at once.
export default {
  content: [
    './themes/trg-networking/**/*.php',
    './plugins/trg-site/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50:  '#EFF6FF',
          100: '#DBEAFE',
          200: '#BFDBFE',
          400: '#60A5FA',
          500: '#3B82F6',
          600: '#2563EB',
          700: '#1D4ED8',
        },
        ink:    '#0F172A',
        body:   '#1E293B',
        muted:  '#475569',
        soft:   '#64748B',
        line:   '#E2E8F0',
        canvas: '#F8FAFC',
        navy:   '#0D2247',
      },
      fontFamily: {
        display: ['Outfit', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        heading: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        sans:    ['Inter', 'system-ui', 'sans-serif'],
      },
      maxWidth: { shell: '1200px' },
      keyframes: {
        marquee: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(-50%)' } },
        fadeUp:  { '0%': { opacity: 0, transform: 'translateY(16px)' }, '100%': { opacity: 1, transform: 'none' } },
      },
      animation: {
        marquee: 'marquee 32s linear infinite',
        fadeUp:  'fadeUp .5s ease-out both',
      },
    },
  },
  // Classes that only ever appear in the database (page content the client
  // edits) would otherwise be stripped by Tailwind's content scan.
  safelist: [
    'bg-white', 'bg-canvas', 'bg-ink',
    'sm:grid-cols-2', 'lg:grid-cols-3', 'lg:grid-cols-4',
    'order-1', 'order-2', 'lg:order-1', 'lg:order-2',
  ],
  plugins: [],
}

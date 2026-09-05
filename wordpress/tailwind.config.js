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
        // Lifted from test2.trgnetworking.com, not eyeballed. That build states
        // its colours in oklch; these are the same ramp converted to hex, on
        // its hue (255), with 600 landing exactly on its --primary #0E5CAF.
        brand: {
          50:  '#ECF4FF',
          100: '#D9EAFF',
          200: '#BAD7FC',
          300: '#8EBAF4',
          400: '#4C88D3',
          500: '#2D72C4',
          600: '#0E5CAF',
          700: '#004991',
          800: '#02376F',
          900: '#012854',
        },
        ink:    '#0E1721',
        body:   '#212A34',
        muted:  '#606A74',
        soft:   '#78818B',
        line:   '#DFE5EC',
        canvas: '#F1F4F8',
        navy:   '#012854',
      },
      // test2 uses Outfit for every heading level and Plus Jakarta Sans for
      // body copy. `heading` stays as a separate token because eyebrows,
      // buttons and labels use it, and those are Plus Jakarta Sans there too.
      fontFamily: {
        display: ['Outfit', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        heading: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        sans:    ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      // test2's --radius.
      borderRadius: { xl: '0.875rem', '2xl': '1.25rem' },
      maxWidth: { shell: '1200px' },
      keyframes: {
        marquee: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(-50%)' } },
        fadeUp:  { '0%': { opacity: 0, transform: 'translateY(16px)' }, '100%': { opacity: 1, transform: 'none' } },
        // The two cards floating over the hero image. glideDown brings them in
        // from above on load; drift keeps them moving gently afterwards.
        //
        // driftAlt is the same path half a cycle out of phase, so the two cards
        // never move in lockstep. Deliberately mirrored keyframes rather than a
        // negative animation-delay on `drift`: a negative delay makes the second
        // animation live from frame one, and because it is declared after
        // glideDown it would win the transform and swallow the entrance.
        glideDown: { '0%': { opacity: 0, transform: 'translateY(-30px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
        drift:     { '0%,100%': { transform: 'translateY(-10px)' }, '50%': { transform: 'translateY(10px)' } },
        driftAlt:  { '0%,100%': { transform: 'translateY(10px)' }, '50%': { transform: 'translateY(-10px)' } },
      },
      animation: {
        marquee: 'marquee 32s linear infinite',
        fadeUp:  'fadeUp .5s ease-out both',
        // The drift delay matches the end of the glide, so the card is never
        // being pulled by both at once.
        glide:    'glideDown .7s ease-out both, drift 7s ease-in-out .7s infinite',
        glideAlt: 'glideDown .7s ease-out .15s both, driftAlt 7s ease-in-out .85s infinite',
      },
    },
  },
  // Classes that only ever appear in the database (page content the client
  // edits) would otherwise be stripped by Tailwind's content scan.
  safelist: [
    'bg-white', 'bg-canvas', 'bg-ink',
    'sm:grid-cols-2', 'lg:grid-cols-3', 'lg:grid-cols-4',
    'md:grid-cols-3', 'md:grid-cols-4',
    'order-1', 'order-2', 'lg:order-1', 'lg:order-2', 'min-w-0',
  ],
  plugins: [],
}

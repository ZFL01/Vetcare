/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./pages/*.php",
    "./includes/*.php",
    "./*.html"
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
        'display': ['Poppins', 'Inter', 'system-ui', 'sans-serif'],
      },
      colors: {
        primary: {
          DEFAULT: 'hsl(270 70% 50%)',
          light: 'hsl(270 70% 65%)',
          dark: 'hsl(270 70% 35%)',
        },
        secondary: {
          DEFAULT: 'hsl(280 60% 60%)',
          light: 'hsl(280 60% 75%)',
        },
        success: 'hsl(270 60% 55%)',
        warning: 'hsl(270 65% 60%)',
      },
      backgroundImage: {
        'gradient-hero': 'linear-gradient(135deg, hsl(270 70% 50%) 0%, hsl(275 65% 55%) 50%, hsl(280 60% 60%) 100%)',
        'gradient-primary': 'linear-gradient(135deg, hsl(270 70% 50%) 0%, hsl(270 70% 65%) 100%)',
        'gradient-secondary': 'linear-gradient(135deg, hsl(280 60% 60%) 0%, hsl(280 60% 75%) 100%)',
        'gradient-glass': 'linear-gradient(145deg, hsl(270 50% 90% / 0.15), hsl(270 50% 85% / 0.08))',
      },
      boxShadow: {
        'glow': '0 0 40px hsl(270 70% 50% / 0.35)',
        'hero': '0 20px 60px hsl(270 70% 50% / 0.25)',
        'card': '0 8px 32px hsl(270 70% 50% / 0.18)',
      },
      animation: {
        'fade-in': 'fade-in 0.5s ease-out',
        'slide-up': 'slide-up 0.6s ease-out',
        'gradient-shift': 'gradient-shift 3s ease-in-out infinite',
        'scale-bounce': 'scale-bounce 2s ease-in-out infinite',
        'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
      },
      keyframes: {
        'fade-in': {
          from: { opacity: '0' },
          to: { opacity: '1' },
        },
        'slide-up': {
          from: { opacity: '0', transform: 'translateY(20px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        'gradient-shift': {
          '0%, 100%': { backgroundPosition: '0% 50%' },
          '50%': { backgroundPosition: '100% 50%' },
        },
        'scale-bounce': {
          '0%': { transform: 'scale(1)' },
          '50%': { transform: 'scale(1.05)' },
          '100%': { transform: 'scale(1)' },
        },
        'pulse-glow': {
          '0%, 100%': { boxShadow: '0 0 20px hsl(270 70% 50% / 0.4)' },
          '50%': { boxShadow: '0 0 40px hsl(270 70% 50% / 0.8)' },
        },
      },
    },
  },
  plugins: [],
}

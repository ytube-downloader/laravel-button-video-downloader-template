/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        'purple-main': '#6c5ce7',
        'heading-main': '#2D3436',
        'dark-heading-main': '#FFFFFF',
        'base-one': '#4A5455',
        'dark-base-one': '#b8b8b8',
        'dark-body': '#121316',
        'dark-heading': '#191a1d',
        'partner': '#9ca3af'
      },
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
      }
    },
  },
  plugins: [],
}
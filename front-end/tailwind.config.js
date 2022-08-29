/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/index.html',
    './src/**/*.{vue,js,ts,html}'
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('tailwindcss-debug-screens')
  ],
}

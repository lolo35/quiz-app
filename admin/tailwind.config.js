/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/index.html',
    './src/**/*.{vue,ts,js}'
  ],
  theme: {
    extend: {
      colors: {
        autblue: "#0078D7"
      }
    },
  },
  plugins: [
    require('tailwindcss-debug-screens')
  ],
}

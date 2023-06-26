/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {},
  },
  plugins: [],
}

// npx tailwindcss -i ./assets/core.css -o ./assets/tailwind.css --minify
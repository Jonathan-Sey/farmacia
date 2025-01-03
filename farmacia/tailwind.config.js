/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue"
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('daisyui')
  ],


/**

  daisyui:{
    themes: [
        {
            mytheme: {                          // nombre del tema
              "primary": "#570df8",
              "secondary": "#f000b8",
              "accent": "#37cdbe",
              "neutral": "#3d4451",
              "base-100": "#ffffff",           // color de fondo
              "info": "#3ABFF8",
              "success": "#36D399",
              "warning": "#FBBD23",
              "error": "#F87272",
            },
          },
          "light", // Puedes usar "light" para un tema claro
          "dark",  // O "dark" para un tema oscuro
    ]
  }
    */
}


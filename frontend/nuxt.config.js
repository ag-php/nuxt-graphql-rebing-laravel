
require('dotenv').config();

module.exports = {
  mode: 'universal',
  env: {
    //gqlEndpoint: process.env.GQL_ENDPOINT || 'http://127.0.0.1:8000/graphql'
    gqlEndpoint: process.env.GQL_ENDPOINT || 'https://backend.axisplanningandconsulting.com/graphql'
  },
  /*
  ** Headers of the page
  */
  head: {
    title: process.env.npm_package_name || 'Axis Planning and Consulting',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || 'Axis Planning and Consulting' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },
  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },
  /*
  ** Global CSS
  */
  css: [
    'element-ui/lib/theme-chalk/index.css',
    '@/assets/styles/index.scss'
  ],
  pwa: {
    manifest: {
      name: 'Axis Planning and Consulting',
      short_name: "Axis",
      lang: 'en',
      start_url: "https://dev.axisplanningandconsulting.com/",
      display: "standalone"
    },
    icon: {
      iconFileName: 'favicon.ico'
    }
  },
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    '@/plugins/element-ui',
    '@/plugins/icons',
    { src: '@/plugins/snap', mode: 'client' }
  ],
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    '@nuxtjs/pwa',
    // Doc: https://github.com/nuxt-community/dotenv-module
    '@nuxtjs/dotenv',
  ],
  /*
  ** Build configuration
  */
  build: {
    transpile: [/^element-ui/],
    /*
    ** You can extend webpack config here
    */
    extend (config, ctx) {
      config.module.rules.push({
        test: require.resolve('snapsvg'),
        use: 'imports-loader?this=>window,fix=>module.exports=0',
      });
    }
  }
}
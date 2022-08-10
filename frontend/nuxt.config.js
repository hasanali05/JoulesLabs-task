export default {
  ssr: false,
  target: 'static',
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'frontend',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: '/css/bootstrap.min.css' },
      { rel: 'stylesheet', href: '/css/lineicons.css' },
    ],
    script: [
      {
        src: '/js/bootstrap.bundle.min.js',
      }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '~/plugins/globalMixinsPlugin',
    '~/plugins/globalComponentsPlugin',
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/dayjs',
    'nuxt-sweetalert2'
  ],

  auth: {
    strategies: {
      'local': {
        provider: 'laravel/sanctum',
        // url: "https://blooming-beach-30533.herokuapp.com",
        url: process.env.BASE_URL,
        autoFetchUser: false,
        token: {
          property: 'access_token',
          maxAge: 60 * 60 * 12,
          global: true,
          type: 'Bearer'
        },
        user: {
            property: false,
            autoFetch: false
        },
        endpoints: {
          login: { url: '/api/login', method: 'post' },
          logout: { url: '/api/logout', method: 'post' },
          user: { url: '/api/user', method: 'get', propertyName: false },
        }
      },
    },
    redirect: {
      login: '/login',
      logout: '/login',
      home: '/'
    },
  },

  
  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    // baseURL: "https://blooming-beach-30533.herokuapp.com/api",
    baseURL: process.env.BASE_URL,
    credentials: true,
    headers: {
      common: {
        'Accept': 'application/json',
      },
    }
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  }
}

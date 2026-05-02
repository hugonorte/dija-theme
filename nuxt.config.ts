/* eslint-disable nuxt/nuxt-config-keys-order */
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/ui'
  ],
  app: {
    buildAssetsDir: '/wp-content/themes/dija-theme/_nuxt/'
  },

  runtimeConfig: {
    public: {
      wpApiUrl: process.env.NUXT_PUBLIC_WP_API_URL || 'https://dija.com.br/wp-json/wp/v2/'
    }
  },

  css: ['~/assets/css/main.css'],

  routeRules: {
    '/': { prerender: true }
  },

  compatibilityDate: '2025-01-15',

  eslint: {
    config: {
      stylistic: {
        commaDangle: 'never',
        braceStyle: '1tbs'
      }
    }
  },

  typescript: {
    typeCheck: false
  }
})

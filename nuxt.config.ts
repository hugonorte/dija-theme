/* eslint-disable nuxt/nuxt-config-keys-order */
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/ui'
  ],
  experimental: {
    payloadExtraction: false,
    appManifest: false
  },
  nitro: {
    prerender: {
      crawlLinks: false
    }
  },
  app: {
    head: {
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/wp-content/themes/dija-theme/favicon.ico' }
      ]
    },
    baseURL: '/',
    buildAssetsDir: '_nuxt/',
    cdnURL: '/wp-content/themes/dija-theme/'
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

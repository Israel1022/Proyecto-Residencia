/**
 * plugins/vuetify.js
 *
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

// Composables
import { createVuetify } from 'vuetify'

// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
export default createVuetify({
  icons: {
    iconfont: 'mdi',
  },
  breakpoint: {
    thresholds: {
        xs: 340,
        sm: 540,
        md: 800,
        lg: 1280,
    },
    scrollBarWidth: 24,
  },
  theme: {
    // defaultTheme: 'dark',
  },
})

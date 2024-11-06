/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import vuetify from './vuetify'
import router from '@/router'
import store from '@/store/store'

import NavigatorDrawer from '@/components/layout/NavigatorDrawer.vue'
import LoadingDialog from '@/components/dialogs/MainProcessLoadingDialog.vue'
import MessageDialog from '@/components/dialogs/MainMessageDialog.vue'

import FormDialog from '@/components/dialogs/MainFormDialog.vue'

export function registerPlugins (app) {
  app
    .use(vuetify)
    .use(router)
    .use(store)
    .component('NavigatorDrawer', NavigatorDrawer)
    .component('LoadingDialog', LoadingDialog)
    .component('FormDialog', FormDialog)
    .component('MessageDialog', MessageDialog)
}

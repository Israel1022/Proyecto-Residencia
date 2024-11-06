<template>
  <div id="app">
    <v-app id="inspire">
      <template v-if="!$route.meta.requiresAuth">
        <v-main>
          <router-view />
        </v-main>        
      </template>
      <template v-else>
        <NavigatorDrawer/>
        <v-main>
          <router-view/>
        </v-main>
        <FormDialog />
        <MessageDialog />
      </template>
    </v-app>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  computed: {
    ...mapGetters(['get_MainFormDialog'])
  },
}
</script>

<style scoped>
</style>
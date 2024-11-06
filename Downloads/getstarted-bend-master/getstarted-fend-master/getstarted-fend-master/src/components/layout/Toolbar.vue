<template>
  <v-app-bar class="bg-blue-lighten-2">
    <v-app-bar-nav-icon color="white" @click="toggleNavigationBar"></v-app-bar-nav-icon>
    <v-toolbar-title></v-toolbar-title>
    <v-spacer></v-spacer>
    <v-btn class="mr-5" icon="mdi-bell"></v-btn><!--mdi-bell-outline -->
    <v-menu>
      <template v-slot:activator="{ props }">
        <!-- <v-btn icon="mdi-account" v-bind="props"></v-btn> -->
        <v-avatar class="mr-5" image="@/assets/image/user.png" v-bind="props"></v-avatar>
      </template>
      <v-card min-width="200">
        <v-list nav density="compact">
          <v-list-item>
            <template v-slot:prepend>
              <v-icon icon="mdi-account-settings-outline" color="blue-grey-darken-3"></v-icon>
            </template>
            <v-list-item-title>
                <span class="font-weight-bold text-uppercase">Perfil</span>
            </v-list-item-title>
          </v-list-item>
          <v-list-item>
            <template v-slot:prepend>
              <v-icon icon="mdi-message" color="blue-grey-darken-3"></v-icon>
            </template>
            <v-list-item-title>
                <span class="font-weight-bold text-uppercase">Mensajes</span>
            </v-list-item-title>
          </v-list-item>

          <v-list-item>
            <div class="pt-4 pb-4 px-5 text-center">
              <v-btn color="primary" variant="outlined" block>Salir</v-btn>
            </div>
          </v-list-item>
        </v-list>
      </v-card>
    </v-menu>
  </v-app-bar>
</template>
<script>
export default {
  props:['isactive'],
  data: () => ({ drawer: true }),
  methods: {
    toggleNavigationBar () {
      const vm = this
      vm.$emit('toggleNavigationBar')
    },
  },
  watch: {
    drawer (active) {
        this.isactive(active)
    }
  },
}
</script>

<style>

</style>
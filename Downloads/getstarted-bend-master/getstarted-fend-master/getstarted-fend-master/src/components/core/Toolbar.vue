<template>
    <v-app-bar app class="indigo darken-1" dark >
      <v-app-bar-nav-icon @click.stop="toggleNavigationBar"></v-app-bar-nav-icon>
      <v-toolbar-title>
        <span class="font-weight-bold font-weight-black title">{{ name }}</span>
      </v-toolbar-title>
      <div class="flex-grow-1"></div>
      <breadcrumbs />
    </v-app-bar>
</template>

<script>
export default {
  props: ['name'],
  data: () => ({

  }),
  methods: {
    toggleNavigationBar () {
      const vm = this
      vm.$emit('toggleNavigationBar')
    }
  }
}
</script>

<template>
  <v-breadcrumbs :items="items" class="breadcrumbs" dark divider="/">
    <template slot="item" slot-scope="props">
      <router-link :to="{ name: props.item.href }" class="link">{{ props.item.name }}</router-link>
    </template>
  </v-breadcrumbs>
</template>

<script>
export default {
  data () {
    return {
      items: []
    }
  },

  watch: {
    $route () {
      const vm = this
      vm.updateBreadcrumbs()
    }
  },

  mounted () {
    const vm = this
    vm.updateBreadcrumbs()
  },

  methods: {
    updateBreadcrumbs () {
      const vm = this
      vm.items = vm.$route.meta.breadcrumb
    }
  }
}
</script>

<style>
.breadcrumbs .link {
  text-decoration: none;
  color: white;
  font: 14px Sans-Serif;
  letter-spacing: 1px;
  white-space: nowrap;
  display: inline-block;
  position: relative;
}
.v-breadcrumbs__divider {
  color: #737373;
  font: bold 14px Sans-Serif;
  letter-spacing: -1px;
  white-space: nowrap;
  position: relative;
}
</style>

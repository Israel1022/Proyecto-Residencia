<template>
  <v-list-item @click="ItemAction(item.action)" class="text-caption" variant="plain">
    <template v-slot:prepend>
      <v-icon :icon="item.icon" color="blue-grey-darken-3"></v-icon>
    </template>
    <v-list-item-title>
      <span class="font-weight-bold text-uppercase">{{item.title}}</span>
    </v-list-item-title>
  </v-list-item>
</template>
<script>
export default {
  props: ['item', 'OnSuccess'],
  methods: {
    ItemAction (action) {
      if (this.OnSuccess) this.OnSuccess(action)
    }
  }
}
</script>
<style scoped>
</style>
  
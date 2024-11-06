<template>
  <v-tooltip :text="item.title">
    <template v-slot:activator="{ props }">
      <v-btn v-bind="props" class="ma-1 bg-blue-lighten-2" @click="ItemAction(item.action)" icon>
        <v-icon color="white">{{ item.icon }}</v-icon>
      </v-btn>
    </template>
  </v-tooltip>
</template>
<script>
export default {
  props:['item', 'OnSuccess'],
  data: () => ({}),
  mounted () {
  },
  methods: {
    ItemAction (action) {
      if (this.OnSuccess) this.OnSuccess(action)
    }
  }
}
</script>  
<style scoped>
</style>
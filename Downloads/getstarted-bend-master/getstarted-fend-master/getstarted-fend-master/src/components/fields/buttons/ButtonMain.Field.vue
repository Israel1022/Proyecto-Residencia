<template>
  <v-btn class="text-body-2 ml-1"
    @click="ItemAction(item)"
    :color="item.color"
    :prepend-icon="item.icon"
    variant="flat">
    {{ item.title }}
  </v-btn>
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
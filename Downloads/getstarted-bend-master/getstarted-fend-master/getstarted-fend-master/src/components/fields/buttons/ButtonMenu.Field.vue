<template>
  <v-menu v-model="menu" :close-on-content-click="false" location="end">
    <template v-slot:activator="{ props }">
      <v-btn v-bind="props" class="text-body-2 pt-1 ml-1"
        :color="item.color"
        :prepend-icon="item.icon"
        variant="flat">
        {{ item.title }}
      </v-btn>
    </template>
    <v-card min-width="300" max-height="400">
      <v-list nav density="compact">
        <span v-for="(option, index) in item.options" :key="index">
          <v-list-item v-if="!option.hidden" @click="ItemAction(option)" class="text-caption" variant="plain">
            <template v-slot:prepend>
              <v-icon :icon="option.icon" color="blue-grey-darken-3"></v-icon>
            </template>
            <v-list-item-title>
              <span class="font-weight-bold text-uppercase">{{option.title}}</span>
            </v-list-item-title>
          </v-list-item>
        </span>
      </v-list>
    </v-card>
  </v-menu>
</template>
<script>
export default {
  props:['item', 'OnSuccess'],
  data: () => ({
    menu: false
  }),
  components:{ },
  mounted() {
    console.log('item', this.item)
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
<template>
  <v-menu v-model="menu" :close-on-content-click="false" location="end">
    <template v-slot:activator="{ props: menu }">
      <v-tooltip :text="item.title">
        <template v-slot:activator="{ props: tooltip }">
          <v-btn v-bind="mergeProps(menu, tooltip)" class="ma-1 bg-blue-lighten-2" icon>
            <v-icon color="white">{{ item.icon }}</v-icon>
          </v-btn>
        </template>
      </v-tooltip>

    </template>
    <v-card min-width="300" max-height="400">
      <v-list nav density="compact">
        <span v-for="(i, index) in item.items" :key="index">
          <ListItemGroup v-if="i.group" :item="i" :OnSuccess="ItemAction" />
          <ListItem v-else :item="i" :OnSuccess="ItemAction"/>
        </span>
      </v-list>
    </v-card>
  </v-menu>
</template>
<script>
import { mergeProps } from 'vue'
import ListItem from '@/components/fields/list_item/ListItem.Field.vue'
import ListItemGroup from '@/components/fields/list_item/ListItemGroup.Field.vue'
export default {
  props:['item', 'OnSuccess'],
  data: () => ({
    menu: false
  }),
  components:{ ListItem, ListItemGroup },
  methods: {
    mergeProps,
    ItemAction (action) {
      if (this.OnSuccess) this.OnSuccess(action)
    }
  }
}
</script>  
<style scoped>
</style>
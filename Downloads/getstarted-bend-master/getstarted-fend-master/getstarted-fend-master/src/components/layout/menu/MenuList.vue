<template>
  <v-list nav density="compact">
    <span v-for="(i, index) in menu" :key="index">
      <ListItemGroup v-if="i.group" :item="i" :OnSuccess="ItemAction"/>
      <ListItem v-else :item="i" :OnSuccess="ItemAction"/>
    </span>
  </v-list>
</template>
<script>
import ListItem from "@/components/fields/list_item/ListItem.Field.vue"
import ListItemGroup from "@/components/fields/list_item/ListItemGroup.Field.vue"
export default {
  props: ['menu', 'OnSuccess'],
  data: () => ({}),
  components: { ListItem, ListItemGroup },
  methods: {
    ItemAction (action) {
      if (this.OnSuccess) this.OnSuccess(action)
    }
  },
}
</script>
<style scoped></style>
<template>
  <v-list-group :value="item.title">
    <template v-slot:activator="{ props }">
      <v-list-item v-bind="props" class="text-caption" variant="plain">
        <template v-slot:prepend>
            <v-icon :icon="item.icon" color="blue-grey-darken-3"></v-icon>
        </template>
        <v-list-item-title>
          <span class="font-weight-bold text-uppercase">{{item.title}}</span>
        </v-list-item-title>
      </v-list-item>
    </template>

    <span v-for="(i, index) in item.items" :key="index">
      <ListItemGroupTwo v-if="i.group" :item="i" :OnSuccess="ItemAction" />
      <ListItem v-else :item="i" :OnSuccess="ItemAction"/>
    </span>
</v-list-group>

</template>
<script>
import ListItem from "@/components/fields/list_item/ListItem.Field.vue"
import ListItemGroupTwo from "@/components/fields/list_item/ListItemGroupTwo.Field.vue"
export default {
  name: 'ListItemGroup',
  props: ['item', 'OnSuccess'],
  components: { ListItem, ListItemGroupTwo },
  methods: {
    ItemAction (action) {
      if (this.OnSuccess) this.OnSuccess(action)
    }
  }
}
</script>
<style scoped>
</style>

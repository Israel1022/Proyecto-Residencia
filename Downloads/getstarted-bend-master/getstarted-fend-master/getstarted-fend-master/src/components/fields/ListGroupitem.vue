<template>
  <v-list-group
    no-action
    :prepend-icon="item.icon"
    :class="item.hidden"
    dense
  >
    <template #activator>
      <v-list-item-content>
        <v-list-item-subtitle class="font-weight-bold black--text">
          {{ item.title }}
        </v-list-item-subtitle>
      </v-list-item-content>
    </template>
    <span
      v-for="(subItem, j) in item.items"
      :key="j"
    >
      <ListGroupitemTwo
        v-if="subItem.group"
        :item="subItem"
        :on-success="ItemAction"
      />
      <ListItem
        v-else
        :item="subItem"
        :on-success="ItemAction"
      />
    </span>
  </v-list-group>
</template>

<script>
import ListItem from '@/components/fields/ListItem'
import ListGroupitemTwo from '@/components/fields/ListGroupitemTwo'

export default {
  name: 'GroupTtem',
  components: {
    ListItem,
    ListGroupitemTwo
  },
  props: ['item', 'OnSuccess'],
  data: () => ({}),
  methods: {
    ItemAction (item) {
      this.OnSuccess(item)
    }
  }
}
</script>
<style>
</style>

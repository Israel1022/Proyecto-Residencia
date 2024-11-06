<template>
  <v-list-group
    :class="item.hidden"
    no-action
    sub-group
    dense
  >
    <template #activator>
      <v-list-item-content>
        <v-list-item-subtitle class="font-weight-bold">
          {{ item.title }}
        </v-list-item-subtitle>
      </v-list-item-content>
    </template>
    <span
      v-for="(subItem, j) in item.items"
      :key="j"
    >
      <ListGroupitem
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
import ListGroupitem from '@/components/fields/ListGroupitem'

export default {
  name: 'GroupTtemtwo',
  components: {
    ListItem,
    ListGroupitem
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

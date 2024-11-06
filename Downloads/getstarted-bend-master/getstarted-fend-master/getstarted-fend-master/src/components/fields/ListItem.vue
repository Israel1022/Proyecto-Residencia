<template>
  <span v-if="main">
    <v-list-item
      link
      dense
      :class="item.hidden"
      @click="ItemAction(item)"
      v-show="(item.hidden === '')"
    >
      <v-list-item-icon>
        <v-icon>{{ item.icon }}</v-icon>
      </v-list-item-icon>
      <v-list-item-content>
        <v-list-item-subtitle>
          <span class="font-weight-bold black--text">{{ item.title }}</span>
        </v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
  </span>
  <span v-else>
    <v-list-item
      link
      dense
      :class="item.hidden"
      @click="ItemAction(item)"
      v-show="(item.hidden === '')"
    >
      <v-list-item-subtitle>
        <span class="font-weight-bold">{{ item.title }}</span>
      </v-list-item-subtitle>
      <v-list-item-icon>
        <v-icon>{{ item.icon }}</v-icon>
      </v-list-item-icon>
    </v-list-item>
  </span>
</template>
<script>
export default {
  props: ['item', 'main', 'OnSuccess'],
  methods: {
    ItemAction (item) {
      this.OnSuccess(item)
    }
  }
}
</script>

<style>

</style>

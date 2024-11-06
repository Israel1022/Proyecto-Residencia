<template>
  <v-treeview
    v-model="items"
    :items="data"
    selection-type="leaf"
    :open.sync="open"
    :filter="filter"
    :search="search"
    open-on-click
    transition
    selectable
    return-object>
    <template v-slot:prepend="{ item }">
      <!-- <v-icon v-if="item.children.length > 0">mdi-checkbox-blank-circle</v-icon>
      <v-icon v-else>mdi-checkbox-blank-circle-outline</v-icon> -->
      <span class="font-weight-bold">
        {{ itemText(item) }}
      </span>

      </template>
    </v-treeview>
</template>

<script>
import axios from 'axios'
export default {
  name: 'SelectedMain',
  props: ['itemAction', 'onSuccess', 'setterModel'],
  data: () => ({
    active: [],
    open: [1, 2],
    search: null,
    caseSensitive: false,
    data: [],
    items: []
  }),
  created () {
    this.getDinamicData()
  },
  computed: {
    selected () {
      if (!this.active.length) return
      console.log(this.active)
      const item = this.active[0]
      return item
    },
    filter () {
      return this.caseSensitive ? (item, search, textKey) => item[textKey].indexOf(search) > -1 : undefined
    }
  },
  methods: {
    itemText (item) {
      if (item && this.itemAction.nameid === 'departamento_id') {
        return `${item.nombre}`
      }
      return 'No Title'
    },
    getDinamicData () {
      const token = this.$cookies.get('token')
      axios.get(`${this.itemAction.url}`, { headers: { Authorization: `Bearer ${token}` } }).then(response => {
        this.data = []
        this.data.push(...response.data)
        this.items = this.setterModel
      }).catch(error => {
        if (error == null) {}
      })
    }
  },
  watch: {
    items (val) {
      // console.log(val)
      this.onSuccess({ id: this.itemAction.nameid, data: val })
    },
    setterModel (val) {
      this.items = val
    }
  }

}
</script>
<style >
</style>

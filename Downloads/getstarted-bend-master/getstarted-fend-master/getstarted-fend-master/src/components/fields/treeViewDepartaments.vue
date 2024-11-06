<template>
  <v-card class="mx-auto">
    <v-sheet class="pa-4 primary lighten-2">
      <h2>{{ itemAction.name }}</h2>
      <v-text-field
        v-if="false"
        v-model="search"
        :label="itemAction.name"
        dark
        flat
        solo-inverted
        hide-details
        clearable
        clear-icon="mdi-close-circle-outline">
      </v-text-field>
    </v-sheet>
    <v-card-text>
        <v-treeview
          :active.sync="active"
          :items="data"
          :open.sync="open"
          :filter="filter"
          :search="search"
          activatable
          color="warning"
          open-on-click
          transition
          return-object>
          <template v-slot:prepend="{ item }">
            <v-icon v-if="item.children"
               v-text="`mdi-${item.id === 1 ? 'home-variant' : 'folder-network'}`"></v-icon>
            <v-icon v-else-if="!item.children"
               v-text="`mdi-${'folder-network'}`"></v-icon>{{ itemText(item) }}
          </template>
        </v-treeview>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'
export default {
  name: 'SelectedMain',
  props: ['itemAction', 'onSuccess'],
  data: () => ({
    active: [],
    open: [1, 2],
    search: null,
    caseSensitive: false,
    data: []
  }),
  mounted () {
    this.getDinamicData()
  },
  computed: {
    selected () {
      if (!this.active.length) return undefined
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
      }).catch(error => {
        if (error == null) {}
      })
    }
  },
  watch: {
    selected (val) {
      if (val) {
        this.onSuccess({ id: this.itemAction.nameid, data: val.id, view: this.itemAction.viewname, viewText: val.nombre })
      } else {
        this.onSuccess({ id: this.itemAction.nameid, data: '', view: this.itemAction.viewname, viewText: '' })
      }
    }
  }

}
</script>

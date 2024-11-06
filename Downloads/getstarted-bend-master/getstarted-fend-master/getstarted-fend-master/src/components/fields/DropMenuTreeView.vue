<template>
 <div style="display:flex; ">
    <v-menu v-model="menu" :close-on-content-click="false" offset-x>
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="info" class="mx-3" dark v-bind="attrs" v-on="on">
          {{itemAction.name}}
        </v-btn>
      </template>
      <v-card outlined :loading="loading" class="showScroll">
        <v-card-text>
            <v-treeview v-if="items.length > 0"
            v-model="item"
            :items="items"
            selected-color="success"
            @update:active="onUpdate"
            return-object
            open-on-click
            activatable
            transition>
            <!-- :item-key="(itemAction.value)? itemAction.value : 'id'" -->
            <template v-slot:prepend="{ item }">
                <v-icon v-if="item.children" v-text="`mdi-${item.id === 1 ? 'home-variant' : 'folder-network'}`"></v-icon>
                <v-icon v-else-if="!item.children" v-text="`mdi-${'folder-network'}`"></v-icon>
                {{ itemText(item) }}
            </template>
            </v-treeview>
        </v-card-text>
      </v-card>
    </v-menu>
    <v-text-field v-if="showselected"
      :value="itemText(selected)"
      :label="itemAction.name"
      readonly
      outlined
      dense />
 </div>
</template>

<script>
import axios from 'axios'
export default {
  props: ['itemAction', 'onSuccess', 'setterModel'],
  data: () => ({
    menu: false,
    selected: null,
    showselected: false,
    loading: true,
    open: [],
    items: [],
    item: null
  }),
  created () {
    if (this.itemAction.url) {
      this.getDinamicData(this.itemAction.url)
    }
  },
  methods: {
    itemText (item) {
      if (item && this.itemAction.nameid === 'departamento_id') {
        return `${item.nombre}`
      }
      return 'No Title'
    },
    cancel () {
      this.menu = false
    },
    getDinamicData (url) {
      const token = this.$cookies.get('token')
      axios.get(url, { headers: { Authorization: `Bearer ${token}` } }).then(response => {
        this.items = []
        this.items.push(...response.data)
        this.loading = false
        if (this.setterModel) this.subMenu(this.items)
      }).catch(error => {
        console.log('error', error)
        if (error == null) {
        }
      })
    },
    onUpdate (selection) {
      if (selection && selection.length > 0) {
        const item = selection[0]
        this.selected = item
        this.onSuccess({ id: this.itemAction.nameid, data: item[this.itemAction.value] })
        this.cancel()
      } else {
        this.showselected = false
      }
    },
    subMenu (children) {
      for (let index = 0; index < children.length; index++) {
        const element = children[index]
        if (element.children && element.children.length > 0) this.subMenu(element.children)
        else {
          if (element.id === this.setterModel) {
            this.selected = element
            this.showselected = true
            this.item = element
          }
        }
      }
    }

  },
  computed: {},
  watch: {
    selected (val) {
      this.showselected = true
    }
  }
}
</script>

<style scoped>
.showScroll {
  max-height: 450px;
  overflow: auto;
}
</style>

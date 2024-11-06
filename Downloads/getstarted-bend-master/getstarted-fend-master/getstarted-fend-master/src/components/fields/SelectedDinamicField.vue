<template>
  <div>
    <v-select
      v-model="item"
      :items="items"
      :label="itemAction.name"
      :item-text="itemText"
      :item-value=" (itemAction.value) ? itemAction.value: 'id'"
      :disabled="itemAction.disabled"
      :append-outer-icon="(itemAction.loadingdata) ? (loading) ? ' mdi-loading mdi-spin' : 'mdi-refresh' : ''"
      @click:append-outer="openSelect"
      :multiple="itemAction.multiple"
      clearable
      outlined
      dense
    >
      <template v-if="itemAction.multiple" #selection="{ item }">
        <v-list-item-action-text dense>
          <v-chip color="info" label outlined dense> {{ itemText(item) }} </v-chip>
        </v-list-item-action-text>
      </template>
      <template v-else #selection="{ item }">
        <v-list-item-action-text dense>
          <span class="font-weight-bold subtitle-2">{{ itemText(item) }}</span>
        </v-list-item-action-text>
      </template>

      <template v-if="itemAction.multiple" v-slot:item="{ item, attrs, on }">
        <v-list-item v-on="on" v-bind="attrs" #default="{ active }">
          <v-list-item-action>
            <v-checkbox :input-value="active"></v-checkbox>
          </v-list-item-action>
            <v-list-item-content v-if="item.padre_id == 0">
              <span class="font-weight-bold subtitle-2">{{itemText(item)}}</span>
            </v-list-item-content>
            <v-list-item-content v-else class="pl-3" v-text="itemText(item)" />
        </v-list-item>
      </template>
      <template v-else #item="{ item }">
        <v-list-item-action-text dense>
          <span class="font-weight-bold font-weight-black">{{ itemText(item) }}</span>
        </v-list-item-action-text>
      </template>

    </v-select>
  </div>
</template>

<script>
import axios from 'axios'
import { HeaderGral } from '@/store/modules/config'
export default {
  props: ['itemAction', 'onSuccess', 'setterModel'],
  data: () => ({
    loading: false,
    item: null,
    items: [],
    table: {}
  }),
  created () {
    this.laadData()
  },
  methods: {
    laadData () {
      if (this.itemAction.url) {
        if (this.itemAction.post) {
          this.postDinamicData(this.itemAction.url)
          return
        }
        this.getDinamicData(this.itemAction.url)
      } else {
        this.items = []
        this.items.push(...this.itemAction.items)
      }
    },
    itemText (item) {
      if (item && (this.itemAction.nameid === 'empresa_id')) return `${item.nombre}`
      if (item && (this.itemAction.nameid === 'caracteristica_id')) return `${item.nombre} - ${item.tipo_dato.nombre}`
      if (item && this.itemAction.nameid === 'articulo_id') return `${item.caracteristicas.codigo} - ${item.caracteristicas.descripcion}, ${item.caracteristicas.marca}, ${item.caracteristicas.modelo}, ${item.udm.nombre}`
      if (item && this.itemAction.nameid === 'almacen_id') return `${item.sucursal.nombre} - ${item.nombre}`
      return `${item.nombre}`
    },
    getDinamicData (url) {
      const header = HeaderGral()
      const p = (this.itemAction.params) ? this.itemAction.params : ''
      axios.get(`${url}${p}`, header).then((response) => {
        this.items = []
        this.items.push(...response.data)
        this.loading = false
      }).catch((error) => {
        if (error == null) console.log('Error')
      })
    },
    postDinamicData (url) {
      const header = HeaderGral()
      axios.post(url, {}, header).then((response) => {
        this.items = []
        this.items.push(...response.data)
        this.loading = false
      }).catch((error) => {
        if (error == null) console.log('Error')
      })
    },
    setDefault (items) {
      if (this.itemAction.default) {
        if (this.itemAction.nameid === 'ejercicio_id') {
          const year = new Date().toISOString().substr(0, 4)
          items.map((periodo) => {
            if (periodo.anio >= year) this.item = periodo.id
          })
        }
      }
    },
    openSelect () {
      this.loading = true
      setTimeout(() => { this.laadData() }, 1000)
    }
  },
  watch: {
    item (val) {
      const model = this.items.find((item) => item[this.itemAction.value] === val)
      this.onSuccess({
        id: this.itemAction.nameid,
        data: val,
        nameObject: this.itemAction.objectname,
        objectMain: model,
        fieldMain: this.itemAction
      })
    },
    items (vals) {
      if (this.setterModel) this.item = this.setterModel
    }
  }
}
</script>

<style>
</style>

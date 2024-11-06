<template>
  <v-form ref="formSelect">
    <v-select
      v-model="item"
      :items="items"
      :label="itemAction.name"
      :item-text="itemText"
      :item-value=" (itemAction.itemvalue) ? itemAction.itemvalue: 'id'"
      :readonly="itemAction.readonly"
      clearable
      outlined
      dense>
      <template #selection="{item}">
        {{ itemText(item) }}
      </template>
      <template #item="{ item }">
        <template v-if="item.padre_id == 0">
          <v-list-item-content>
            <span class="font-weight-bold subtitle-2">{{ itemText(item) }}</span>
          </v-list-item-content>
        </template>
        <template v-else>
          <v-list-item-content class="pl-3" v-text="itemText(item)" />
        </template>
      </template>
    </v-select>
  </v-form>
</template>

<script>
import axios from 'axios'
import { DatesUtils } from '@/mixins/datesUtilsMixin'
import { HeaderGral } from '@/store/modules/config'

export default {
  mixins: [DatesUtils],
  props: ['itemAction', 'onSuccess', 'showItems', 'setterModel'],
  data: () => ({
    item: null,
    items: []
  }),
  watch: {
    item (val) {
      if (val) this.onSuccess({ id: this.itemAction.nameid, data: val })
      else this.onSuccess({ id: this.itemAction.nameid, data: null })
    },
    items (vals) {
      if (this.setterModel) this.item = this.setterModel
      else this.setDefault(vals)
    },
    showItems (vals) {
      if (this.itemAction.sub) this.items = this.SubItems(vals)
      else this.items.push(...vals)
    }
  },
  created () {
    if (this.itemAction.url) this.getDinamicData(this.itemAction.url)
  },
  methods: {
    getDinamicData (url) {
      if (url !== '') {
        const header = HeaderGral()
        axios.get(url, header).then((response) => {
          this.items = []
          if (this.itemAction.sub) this.items = this.SubItems(response.data)
          else this.items.push(...response.data)
        }).catch((error) => {
          if (error == null) console.log('Error')
        })
      } else if (this.itemAction.sub) this.items = this.SubItems(this.itemAction.items)
      else this.items.push(...this.itemAction.items)
    },
    itemText (item) {
      if (item && this.itemAction.nameid === 'ejercicio_id') return `${item.descripcion}`
      if (item && this.itemAction.nameid === 'tipo_plaza_id') return `${item.nombre} - ${item.tipo_regimen}`
      return `${item.nombre}`
    },
    SubItems (its) {
      const items = []
      its.map((item) => {
        if (item.padre_id === 0) {
          items.push(item)
          if (item[this.itemAction.sub].length > 0) {
            item[this.itemAction.sub].map((sub) => { items.push(sub) })
          }
        }
      })
      return items
    },
    setDefault (items) {
      if (this.itemAction.default) {
        if (this.itemAction.nameid === 'ejercicio_id') {
          const date = new Date()
          const fecha = this.formatoFecha(date, 'yyyy-mm-dd')
          const today = this.dateStringToDate(fecha)
          items.map((periodo) => {
            if (periodo.padre_id > 0) {
              const start = this.dateStringToDate(periodo.fecha_inicio)
              const end = this.dateStringToDate(periodo.fecha_cierre)

              if (today.getTime() >= start.getTime() && today.getTime() <= end.getTime()) {
                this.item = periodo.id
              }
            }
          })
        }
      }
    }
  }
}
</script>
<style>
</style>

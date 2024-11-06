<template>
  <v-autocomplete
    v-model="object"
    :label="itemAction.name"
    :item-text="itemText"
    :items="objects"
    :item-value=" (itemAction.value) ? itemAction.value : 'id'"
    :search-input.sync="findObject"
    no-data-text="Sin Resultados"
    :disabled="itemAction.readonly"
    :append-icon="(loading) ? 'mdi-refresh mdi-spin' : ''"
    clearable
    outlined
    dense
  >
    <template #selection="{ item }">
      <v-list-item-action-text dense>
        <span class="font-weight-bold subtitle-2">{{ itemText(item) }}</span>
      </v-list-item-action-text>
    </template>

    <template #item="{ item }">
      <v-list-item-action-text dense>
        <span class="font-weight-bold font-weight-black">{{ itemText(item) }}</span>
      </v-list-item-action-text>
    </template>
  </v-autocomplete>
</template>

<script>
import axios from 'axios'
import { HeaderGral } from '@/store/modules/config'
export default {
  name: 'SearchDinamicField',
  props: ['itemAction', 'onSuccess', 'setterModel', 'cleared', 'onClear'],
  data: () => ({
    object: null,
    objects: [],
    findObject: null,
    loading: false,
    timeout: null
  }),
  watch: {
    findObject (val) {
      clearTimeout(this.timeout)
      var self = this
      this.timeout = setTimeout(function () {
        clearTimeout(self.timeout)
        if (val === null || val === '') return
        if (self.itemAction.method) {
          self.postDinamicData(val)
        } else {
          self.getDinamicData(val.replace(' ', '_'))
        }
      }, 200)
    },
    object (val) {
      if (!val) {
        this.onSuccess({
          id: this.itemAction.nameid,
          data: null,
          nameObject: this.itemAction.objectname,
          objectMain: null,
          fieldMain: this.itemAction
        })
        return
      }
      const model = this.objects.find(item => item[this.itemAction.value] === val)
      this.onSuccess({
        id: this.itemAction.nameid,
        data: val,
        nameObject: this.itemAction.objectname,
        objectMain: model,
        fieldMain: this.itemAction
      })
    },
    setterModel (val) {
      if (val && val[this.itemAction.objectname]) {
        this.setModel(val)
      }
    },
    cleared (clear) {
      if (clear) {
        this.object = null
        this.objects = []
        this.onClear({ id: this.itemAction.nameid })
      }
    }
  },
  created () { this.setModel(this.setterModel) },
  methods: {
    itemText (item) {
      if (item && (this.itemAction.nameid === 'empleado_id' || this.itemAction.nameid === 'cliente_id')) {
        return `${item.NombreCompleto}`
      } if (item && this.itemAction.nameid === 'articulo_id') {
        return `${item.caracteristicas.codigo} - ${item.caracteristicas.descripcion} - ${item.udm.nombre} - ${(item.caracteristicas.proveedor) ? item.caracteristicas.proveedor : ''}`
      }
      return `${item.nombre}`
    },
    getDinamicData (name) {
      const header = HeaderGral()
      this.loading = true
      axios.get(`${this.itemAction.url}/${name}`, header).then((response) => {
        this.objects = []
        this.objects.push(...response.data)
        this.loading = false
      }).catch((error) => {
        if (error == null) {
          console.log('Error')
        }
      })
    },
    postDinamicData (name) {
      let data = {}
      if (this.itemAction.params) data = this.itemAction.params
      data.name = name
      const header = HeaderGral()
      this.loading = true
      axios.post(`${this.itemAction.url}`, data, header).then((response) => {
        this.objects = []
        this.objects.push(...response.data)
        this.loading = false
      }).catch((error) => {
        if (error === null) {
          console.log('Error')
        }
      })
    },
    clearData (e) {
      this.onSuccess({ id: this.itemAction.nameid, data: null, nameObject: this.itemAction.objectname, objectMain: null, fieldMain: this.itemAction })
    },
    setModel (val) {
      if (val && val[this.itemAction.objectname]) {
        this.object = val[this.itemAction.nameid]
        this.objects = []
        this.objects.push(val[this.itemAction.objectname])
      }
    }
  }
}
</script>
<style>
</style>

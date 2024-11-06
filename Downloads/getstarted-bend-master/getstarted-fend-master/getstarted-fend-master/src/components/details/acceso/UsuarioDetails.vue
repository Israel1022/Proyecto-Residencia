<template>
  <v-card v-if="model.id" class="elevation-4" tile>
    <v-card-text>
      <v-row dense>
        <v-col cols="5">
          <p class="text-md-h4 text-sm-h5 text-center font-weight-bold black--text">Roles</p>
          <DataTableMain :datatable="table" :on-success="successAction" :items-action="tableAction"/>
        </v-col>
        <v-col cols="7">
          <p class="text-md-h4 text-sm-h5 text-center font-weight-bold black--text">Departamentos</p>
          <v-card>
            <v-card-actions>
              <v-layout justify-end class="py-2 px-4">
                <v-btn @click="AddDataDepto()" color="success" dark >Guardar Cambios</v-btn>
              </v-layout>
            </v-card-actions>
            <v-card-text>
              <v-row dense>
                <v-col cols="6" class="scroll-box" v-show="departamentosshow">
                  <SelectedTreeViewMultipleMain :setterModel="departamentos" :itemAction="fieldDepatamentos" :on-success="actionFieldData" />
                </v-col>
                <v-divider inset vertical />
                <v-col cols="5">
                  <v-list lines="one" dense class="pa-0 ma-0">
                    <v-list-item v-for="item in departamentos" :key="item.id">
                      {{ item.nombre }}
                    </v-list-item>
                  </v-list>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>

      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
import DataTableMain from '@/components/tables/DataTableMain'
import SelectedTreeViewMultipleMain from '@/components/fields/SelectedTreeViewMultipleMain'
import { Permission } from '@/mixins/permissionMain'

export default {
  mixins: [Permission],
  props: ['model', 'onSuccess'],
  name: 'UsuarioDetails',
  data: () => ({
    departamentosshow: false,
    departamentos: [],
    table: {
      header: {
        options: [
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newDataRoles', code: 'empresa.usuarios.set-rol' }
        ],
        titles: [
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Acceso', value: 'descripcion.special', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text' }
        ],
        loading: false,
        filter: false
      },
      body: {
        data: [],
        actions: [
          { title: 'Eliminar', icon: 'mdi-delete', color: 'error', action: 'daleteDataRol', code: 'libre', hidden: '' }
        ]
      },
      footer: {
        paging: true
      }
    },
    fieldDepatamentos: {
      name: 'Departamentos',
      nameid: 'departamento_id',
      url: '/empresa/departamentos/children',
      value: 'id',
      rules: true,
      cols: 12
    },
    item_main: { id: 0, departamentos: [] }
  }),
  components: {
    DataTableMain,
    SelectedTreeViewMultipleMain
  },
  mounted () {
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
  },
  watch: {
    model (row) {
      this.departamentosshow = false
      this.departamentos = []
      this.item_main.departamentos = []
      this.table.body.data = []
      this.departamentosshow = true
      this.table.body.data = row.roles
      this.departamentos = row.departamentos_asignados
    }
  },
  methods: {
    successAction (item) {
      item.item = this.model
      this.onSuccess(item)
    },
    tableAction (item) {
      item.model = this.model
      this.onSuccess(item)
    },
    actionFieldData (obj) {
      this.item_main.departamentos = obj.data
    },
    AddDataDepto () {
      this.item_main.id = this.model.id
      const item = { action: 'AddDataDepto', item: this.item_main }
      this.onSuccess(item)
    }
  }
}
</script>
<style scoped>
.scroll-box {
  max-height: 410px;
  overflow-x: auto;
}
.scroll-box::-webkit-scrollbar {
  width: 10px;/* width of the entire scrollbar */
}

.scroll-box::-webkit-scrollbar-track {
  background: #d2d2d2;/* color of the tracking area */
  border-radius: 20px;
}
.scroll-box::-webkit-scrollbar-thumb {
  background-color: #3F51B5;  /* color of the scroll thumb */
  border-radius: 20px;       /* roundness of the scroll thumb */
  border: 3px solid #d2d2d2;/* creates padding around scroll thumb */
}
</style>

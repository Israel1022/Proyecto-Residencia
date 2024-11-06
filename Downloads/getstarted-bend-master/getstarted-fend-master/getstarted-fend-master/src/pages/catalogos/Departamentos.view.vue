<template>
  <v-container>
    <v-row  dense>
      <v-col cols="12">
        <DataTableMain :datatable="table" :on-success="actionTable" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
import DataTableMain from '@/components/fields/tables/DataTableMain.Field.vue'
import { Permission } from '@/mixins/permissionMain'
export default {
  mixins: [Permission],
  data: () => ({
    table: {
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData', code: 'libre', hidden: '' },
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'empresa.departamentos.create-update' }
        ],
        titles: [
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Empresa', value: 'empresa.nombre', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text', align: 'right' }
        ],
        loading: false
      },
      body: {
        data: [],
        actions: [
          { title: 'Activar / Desactivar', icon: 'mdi-checkbox-blank-circle', color: 'grey darken-3', action: 'disableData', code: 'empresa.departamentos.up-down' },
          { title: 'Editar', icon: 'mdi-pencil', color: 'amber darken-3', action: 'updateData', code: 'empresa.departamentos.create-update' },
          { title: 'Eliminar', icon: 'mdi-delete', color: 'red darken-3', action: 'deleteData', code: 'empresa.departamentos.delete' }
        ]
      },
      footer: {
        paging: true
      }
    },
    user: {}
  }),
  components: {
    DataTableMain
  },
  mounted () {
  },
  methods: {
    ...mapActions(['getUrlServices', 'GETObjects', 'PUTDisableEnableObjectService', 'DELETEObjectService']),
    ...mapMutations(['SHOW_MAIN_FORM_DIALOG']),
    actionTable (item) {
      this[item.action](item)
    },
    ExecuteUrl (url) {
      if (this.get_urls != null) {
        const router = this.get_urls[url]
        this.GETObjects({ url: router })
      } else { this.getUrlServices() }
    },
    /* *==========*==========*==========*==========*==========*==========*==========*==========* */
    refreshData (item) {
      this.table.header.loading = true
      this.ExecuteUrl('empresa.departamentos.show')
    },
    newData (item) {
      this.SHOW_DIALOG_MAIN({
        type: 'create',
        with: '40%',
        title: 'Nuevo Departamento',
        body: {
          objectMain: { empresa_id: this.user.empresa_id },
          paramsDialog: {
            form: this.get_DepartamentosForm,
            url: this.get_urls['empresa.departamentos.create-update'],
            message: 'Registro Agregado Correctamente',
            setmodel: true
          }
        }
      })
    },
    disableData  ({ item }) {
      const name = (item.activo === 'si') ? 'Desactivar' : 'Activar'
      this.SHOW_DIALOG_ALERT_MAIN({
        type: 'disable',
        with: '40%',
        title: `${name} Departamento`,
        body: {
          objectMain: item,
          url: this.get_urls['empresa.departamentos.up-down'],
          message: `¿Estás seguro en ${name} el registro?`,
          description: ''
        },
        confirmButtonText: `Sí, ${name}`,
        cancelButtonText: 'No, Cancelar'
      })
    },
    updateData ({ item }) {
      this.SHOW_DIALOG_MAIN({
        type: 'create',
        with: '40%',
        title: 'Editar Departamento',
        body: {
          objectMain: item,
          paramsDialog: {
            form: this.get_DepartamentosForm,
            url: this.get_urls['empresa.departamentos.create-update'],
            message: 'Registro Agregado Correctamente',
            setmodel: true
          }
        }
      })
    },
    deleteData  ({ item }) {
      this.SHOW_DIALOG_ALERT_MAIN({
        type: 'delete',
        with: '40%',
        title: 'Eliminar Departamento',
        body: {
          objectMain: item,
          url: this.get_urls['empresa.departamentos.delete'],
          message: '¿Estás seguro de eliminar el registro?',
          description: 'No podras revertir el movimiento'
        },
        confirmButtonText: 'Sí, Eliminar',
        cancelButtonText: 'No, Cancelar'
      })
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_objects', 'get_dialogMain',
      'get_DepartamentosForm'])
  },
  watch: {
    get_urls (val) { this.refreshData({}) },
    get_objects (vals) {
      if (vals) {
        this.table.body.data = vals
        this.table.header.loading = false
      }
    }
  },
  beforeDestroy () {
    this.CLEAR_OBJECTS()
  }
}
</script>

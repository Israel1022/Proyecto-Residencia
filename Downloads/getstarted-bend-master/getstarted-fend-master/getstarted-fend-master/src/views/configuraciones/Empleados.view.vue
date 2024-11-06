<template>
    <v-container fluid>
        <v-layout justify-center>
            <v-flex md12>
              <DataTableMain :datatable="table" :on-success="SuccessTableAction" :itemsAction="SuccessTableAction"/>
            </v-flex>
        </v-layout>
        <MainFormDialog v-if="get_dialogMain.dialog" :model="objectMain" :params="paramsDialog" />
    </v-container>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
import DataTableMain from '@/components/tables/DataTableMain'
import MainFormDialog from '@/components/dialog/main/MainFormDialog'
import { Permission } from '@/mixins/permissionMain'
export default {
  mixins: [Permission],
  data: () => ({
    table: {
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData', code: 'libre', hidden: '' },
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'empresa.empleados.create-update' }
        ],
        titles: [
          { text: 'Clave', value: 'cve_empleado', class: 'black--text' },
          { text: 'Empleado', value: 'NombreCompleto', class: 'black--text' },
          { text: 'Departamento', value: 'departamento.nombre', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          // { text: 'Fecha Creacion', value: 'Created', class: 'black--text' },
          // { text: 'Fecha Actualizada', value: 'Updated', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text', align: 'right' }
        ],
        filter: true,
        loading: false
      },
      body: {
        data: [],
        actions: [
          { title: 'Activar / Desactivar', icon: 'mdi-checkbox-blank-circle', color: 'grey darken-3', action: 'disableData', code: 'empresa.empleados.up-down' },
          { title: 'Editar', icon: 'mdi-pencil', color: 'amber darken-3', action: 'updateData', code: 'empresa.empleados.create-update' },
          { title: 'Eliminar', icon: 'mdi-delete', color: 'red darken-3', action: 'deleteData', code: 'empresa.empleados.delete' }
        ]
      },
      footer: {
        paging: true
      }
    },
    empleado: {},
    objectMain: {},
    paramsDialog: {}
  }),
  components: {
    DataTableMain,
    MainFormDialog
  },
  mounted () {
    this.refreshData({})
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
  },
  methods: {
    ...mapActions(['getUrlServices', 'GETObjectsService', 'PUTDisableEnableObjectService', 'DELETEObjectService']),
    ...mapMutations(['SHOW_DIALOG_MAIN', 'CLEAR_OBJECTS', 'SET_DATA_SECOUND_MAIN']),
    SuccessTableAction (item) {
      this[item.action](item)
    },
    /* *==========*==========*==========*==========*==========*==========*==========*==========* */
    refreshData (item) {
      this.table.header.loading = true
      this.ExecuteUrl('empresa.empleados.show')
    },
    newData (item) {
      this.objectMain = {}
      this.paramsDialog = {
        form: this.get_EmpleadoForm,
        url: this.get_urls['empresa.empleados.create-update'],
        message: 'Registro Agregado Correctamente'
      }
      this.SHOW_DIALOG_MAIN({ type: 'create', with: 850, title: 'Nuevo Empleado' })
    },
    disableData  ({ item }) {
      const name = (item.activo === 'si') ? 'Desactivar' : 'Activar'
      this.$swal.fire({
        title: `¿Estás seguro de ${name} el registro ?`,
        // message: 'No podras revertir el movimiento',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Si, ${name}`,
        cancelButtonText: 'No, Cancelar'
      }).then((result) => {
        if (result.value) {
          const url = this.get_urls['empresa.empleados.up-down']
          this.PUTDisableEnableObjectService({ url: url, params: item.id, replace: '{id}' })
        }
      })
    },
    updateData ({ item }) {
      this.objectMain = item
      this.paramsDialog = {
        form: this.get_EmpleadoForm,
        url: this.get_urls['empresa.empleados.create-update'],
        message: 'Registro Actualizado Correctamente',
        setmodel: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'create', with: 850, title: 'Editar Empleado' })
    },
    deleteData  ({ item }) {
      this.$swal.fire({
        title: '¿Estás seguro de eliminar el registro?',
        message: 'No podras revertir el movimiento',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Eliminar',
        cancelButtonText: 'No, Cancelar'
      }).then((result) => {
        if (result.value) {
          this.table.header.loading = true
          const url = this.get_urls['empresa.empleados.delete']
          this.DELETEObjectService({ url: url, params: item.id, replace: '{id}' })
        }
      })
    },
    /* *==========*==========*==========*==========*==========*==========*==========*==========* */
    ExecuteUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.GETObjectsService({ url: router })
      } else {
        this.getUrlServices()
      }
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_dialogMain', 'get_objects',
      'get_EmpleadoForm'])
  },
  watch: {
    get_urls () { this.refreshData({}) },
    get_objects (vals) {
      if (vals) {
        this.table.header.loading = false
        this.table.body.data = vals
      }
    }
  },
  beforeDestroy () {
    this.CLEAR_OBJECTS()
  }

}
</script>

<style>

</style>

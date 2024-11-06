<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols="12">
        <DataTableMain :datatable="table" :on-success="SuccessTableAction" :itemsAction="SuccessTableAction" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
import DataTableMain from '@/components/tables/DataTableMain'
import { Permission } from '@/mixins/permissionMain'
export default {
  mixins: [Permission],
  data: () => ({
    table: {
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData', code: 'libre', hidden: '' },
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'catalogo.caracteristicas.create-update' }
        ],
        titles: [
          { text: 'Codigo Area', value: 'nombre', class: 'black--text' },
          // { text: 'Serie', value: 'tipo_dato.nombre', class: 'black--text' },
          // { text: 'Subserie', value: 'DinamicObjectData', class: 'black--text' },
          // { text: 'Area', value: 'Activo', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text', align: 'right' }
        ],
        loading: true
      },
      body: {
        data: [],
        actions: [
          { title: 'Activar / Desactivar', icon: 'mdi-checkbox-blank-circle', color: 'grey darken-3', action: 'disableData', code: 'catalogo.caracteristicas.up-down' },
          { title: 'Editar', icon: 'mdi-pencil', color: 'amber darken-3', action: 'updateData', code: 'catalogo.caracteristicas.create-update' }
          // { title: 'Eliminar', icon: 'mdi-delete', color: 'red darken-3', action: 'deleteData', code: 'catalogo.caracteristicas.delete' }
        ]
      },
      footer: {
        paging: true
      }
    }
  }),
  components: {
    DataTableMain
  },
  mounted () {
    this.refreshData({})
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
  },
  methods: {
    ...mapActions(['getUrlServices', 'GETObjects', 'PUTDisableEnableObjectService']),
    ...mapMutations(['CLEAR_OBJECTS', 'SHOW_DIALOG_MAIN']),
    SuccessTableAction (item) {
      this[item.action](item)
    },
    ExecuteUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.GETObjects({ url: router })
      } else {
        this.getUrlServices()
      }
    },
    /* *==========*==========*==========*==========*==========*==========*==========*==========* */
    refreshData (item) {
      this.table.header.loading = true
      this.ExecuteUrl('empresa.empleados.show')
    },
    newData () {
      this.SHOW_DIALOG_MAIN({
        type: 'create',
        with: '50%',
        title: 'Nueva Característica',
        body: {
          objectMain: {},
          paramsDialog: {
            form: this.get_CaracteristicasForm,
            url: this.get_urls['catalogo.caracteristicas.create-update'],
            message: 'Registro Agregado Correctamente'
          }
        }
      })
    },
    disableData  ({ item }) {
      const name = (item.activo === 'si') ? 'Desactivar' : 'Activar'
      this.$swal.fire({
        title: `¿Estás seguro de ${name} el registro?`,
        // message: 'No podras revertir el movimiento',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sí, ${name}`,
        cancelButtonText: 'No, Cancelar'
      }).then((result) => {
        if (result.value) {
          const url = this.get_urls['catalogo.caracteristicas.up-down']
          this.PUTDisableEnableObjectService({ url: url, params: item.id, replace: '{id}' })
        }
      })
    },
    updateData ({ item }) {
      this.objectMain = item
      this.paramsDialog = {
        form: this.get_CaracteristicasForm,
        url: this.get_urls['catalogo.caracteristicas.create-update'],
        message: 'Registro Actualizado Correctamente',
        setmodel: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'create', with: 850, title: 'Editar Característica' })
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
          const url = this.get_urls['catalogo.caracteristicas.delete']
          this.DELETEObjectService({ url: url, params: item.id, replace: '{id}' })
        }
      })
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_objects', 'get_CaracteristicasForm'])
  },
  watch: {
    get_urls (val) { this.refreshData({}) },
    get_objects (objects) {
      if (objects) {
        this.table.body.data = objects
        this.table.header.loading = false
      }
    }
  },
  beforeDestroy () {
    // this.CLEAR_OBJECTS()
  }
} // ./tecnologias.view.vue
</script>

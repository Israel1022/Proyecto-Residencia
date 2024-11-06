<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols="12">
        <v-toolbar class="elevation-2">
          <v-row dense class="pt-5">
            <v-col cols="12" sm="6" md="4" v-if="showMainPermiss('catalogo.familias.show-sub-familias')">
              <SelectedGroup :item-action="selectedFamilia" :on-success="actionFieldData" />
            </v-col>
          </v-row>
        </v-toolbar>
        <DataTableMain :datatable="table" :on-success="tableAction" :items-action="tableAction" />
      </v-col>
    </v-row>
    <MainFormDialog v-if="get_dialogMain.dialog" :model="object" :params="paramsDialog" :on-success="dataForm"/>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
import SelectedGroup from '@/components/fields/SelectedGroupDinamicField'
import DataTableMain from '@/components/tables/DataTableMain'
import MainFormDialog from '@/components/dialog/main/MainFormDialog'
import { Permission } from '@/mixins/permissionMain'
export default {
  mixins: [Permission],
  components: {
    SelectedGroup,
    DataTableMain,
    MainFormDialog
  },
  data: () => ({
    selectedFamilia: {
      field: 'selectGroupDataServer',
      name: 'Familia',
      nameid: 'familia_id',
      url: '/catalogo/familias/subfamilias',
      rules: true,
      default: true,
      sub: 'sub_familia',
      cols: 12
    },
    table: {
      header: {
        options: [
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'catalogo.familias-caracteristica.add' }
        ],
        titles: [
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Tipo Dato', value: 'tipo_dato.nombre', class: 'black--text' },
          { text: 'Configuración', value: 'DinamicObjectData', fieldMain: 'tipo_dato.default', type: 'json', class: 'black--text' },
          { text: 'Orden', value: 'pivot.order', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          // { text: 'Created', value: 'Created', class: 'black--text' },
          // { text: 'Updated', value: 'Updated', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text' }
        ],
        loading: false
      },
      body: {
        data: [],
        actions: [{ title: 'Eliminar', icon: 'mdi-delete', color: 'error', action: 'daleteData', code: 'catalogo.familias-caracteristica.remove' }]
      },
      footer: { paging: true }
    },
    params: {},
    paramsDialog: {},
    model: {}
  }),
  mounted () {
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
  },
  methods: {
    ...mapActions(['getUrlServices', 'GETListObjectsService', 'GETObjectService', 'DELETEObjectService']),
    ...mapMutations(['SHOW_DIALOG_MAIN', 'CLEAR_LIST_DATA']),
    showMainPermiss (permiso) {
      return this.ReadOnlyPermiso(JSON.parse(localStorage.getItem('permisos')), permiso)
    },
    dataForm ({ name, model }) {
      if (name === 'FamiliaCaracteristica') {
        this.table.body.data.push(model)
      // const d = JSON.parse(model.tipo_dato.default);
      } else {
        this.refreshData({})
      }
    },
    // *==========*==========*==========*==========* \\
    actionFieldData (obj) {
      this.table.header.loading = true
      this.params[obj.id] = (obj.data) ? obj.data : null
      if (obj.data) {
        this.table.header.loading = true
        this.findCaracteristicas(obj.data)
      } else {
        this.params = {}
        this.table.body.data = []
        this.table.header.loading = false
      }
    },
    findCaracteristicas (id) {
      if (this.get_urls) {
        const router = this.get_urls['catalogo.familias-caracteristica']
        this.GETObjectService({ url: router, replace: '/{id}', params: `/${id}` })
      } else {
        this.getUrlServices()
      }
    },
    tableAction (item) {
      this[item.action](item)
    },
    newData (item) {
      if (!this.params.familia_id) {
        this.$swal({
          type: 'warning',
          icon: 'warning',
          title: 'Advertencia !',
          text: 'Debe seleccionar una familia',
          timer: 1500
        })
        return
      }
      const order = 1
      this.object = { familia_id: this.params.familia_id, order: order }
      this.paramsDialog = {
        form: this.get_FamiliaCaracterisiricForm,
        url: this.get_urls['catalogo.familias-caracteristica.add'],
        message: 'Registro Agregado Correctamente',
        returnObject: true,
        setmodel: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'FamiliaCaracteristica', with: 550, title: 'Agregar Caracteristica' })
    },
    daleteData ({ body, item }) {
      this.$swal.fire({
        title: 'Estas seguro de eliminar el registro ?',
        text: 'No podras revertir este movimiento !',
        width: 600,
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, Eliminar!',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) {
          this.table.header.loading = true
          const router = this.get_urls[body.code]
          this.DELETEObjectService({ url: router, replace: '{familia_id}/{id}', params: `${this.params.familia_id}/${item.id}` })
        }
      })
    }
    // *==========*==========*==========*==========* \\
  },
  computed: {
    ...mapGetters(['get_urls', 'get_dialogMain', 'get_object', 'get_reload', 'get_refreshData',
      'get_FamiliaCaracterisiricForm'])
  },
  watch: {
    get_urls (val) { this.findCaracteristicas(this.params.familia_id) },
    get_object (data) {
      this.table.header.loading = false
      this.table.body.data = data.caracteristicas
    },
    get_reload (data) {
      this.table.body.data.push(data)
      const d = JSON.parse(data.tipo_dato.default)
      d.name = data.nombre
      d.nameid = data.identificador
    },
    get_objectDelete (data) {
      const fields = this.table.body.data.filter(el => el.id !== parseInt(data.id))
      this.table.body.data = fields
      this.table.header.loading = false
    },
    get_refreshData (val) {
      if (this.params.familia_id) this.findCaracteristicas(this.params.familia_id)
    }
  }
}
</script>

<style>

</style>

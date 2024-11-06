<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols="12">
        <DataTableMain :datatable="table" :on-success="successTableAction" :itemsAction="successTableAction"/>
      </v-col>
      <v-col cols="12">
        <ViewDetailsMain class="my-2" :object="objectDetail" type="RolPermisosDetails" :actions="successTableAction" />
      </v-col>
    </v-row>
    <MainFormDialog v-if="get_dialogMain.dialog" :model="objectMain" :params="paramsDialog" />
  </v-container>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
import DataTableMain from '@/components/tables/DataTableMain'
import MainFormDialog from '@/components/dialog/main/MainFormDialog'
import ViewDetailsMain from '@/components/details/ViewDetailsMain'
import { Permission } from '@/mixins/permissionMain'
// import router from '@/router'
export default {
  mixins: [Permission],
  data: () => ({
    table: {
      modelMain: [],
      showSelect: true,
      singleSelect: true,
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData', code: 'libre', hidden: '' },
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'empresa.roles.create-update' }
        ],
        titles: [
          { text: 'Clave', value: 'slug', class: 'black--text' },
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          { text: 'Acceso Especial', value: 'AccessAll', class: 'black--text' },
          // { text: 'Fecha Creacion', value: 'Created', class: 'black--text' },
          // { text: 'Fecha Actualizada', value: 'Updated', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text', align: 'right' }
        ],
        loading: false
      },
      body: {
        data: [],
        actions: [
          { title: 'Editar', icon: 'mdi-pencil', color: 'warning', action: 'updateData', code: 'empresa.roles.create-update' }
        ]
      },
      footer: {
        paging: true
      }
    },
    objectMain: {},
    paramsDialog: {}
  }),
  components: {
    DataTableMain,
    MainFormDialog,
    ViewDetailsMain
  },
  mounted () {
    this.refreshData({})
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
  },
  methods: {
    ...mapActions(['getUrlServices', 'GETObjectsService', 'POSTObjectsService']),
    ...mapMutations(['SHOW_DIALOG_MAIN', 'CLEAR_OBJECTS']),
    /* *==========*==========*==========*==========*==========*==========*==========* */
    successTableAction (item) {
      this[item.action](item)
    },
    refreshData (item) {
      this.table.header.loading = true
      this.getUrl('empresa.roles.show')
    },
    newData (item) {
      this.objectMain = {}
      this.paramsDialog.form = this.get_RoleForm
      this.paramsDialog.url = this.get_urls['empresa.roles.create-update']
      this.paramsDialog.message = 'Registro Agregado Correctamente'
      this.SHOW_DIALOG_MAIN({ type: 'create', with: 850, title: 'Nuevo Rol' })
    },
    updateData ({ item }) {
      this.objectMain = item
      this.paramsDialog = {
        form: this.get_RoleForm,
        url: this.get_urls['empresa.roles.create-update'],
        message: 'Registro Agregado Correctamente',
        setmodel: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'create', with: 850, title: 'Editar Rol' })
    },
    tableAction (item) {
      this[item.action](item)
    },
    permissionsAssign ({ item }) {
      const router = this.get_urls['empresa.roles.set-permiso']
      this.POSTObjectsService({ url: router, data: item })
    },
    getUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.GETObjectsService({ url: router, data: {} })
      } else {
        this.getUrlServices()
      }
    }
  },
  computed: {
    ...mapGetters(['get_dialogMain', 'get_urls', 'get_objects', 'get_object',
      'get_RoleForm']),
    objectDetail () {
      if (this.table.modelMain.length > 0) {
        return this.table.modelMain[0]
      }
      return {}
    }
  },
  watch: {
    get_urls () { this.refreshData({}) },
    get_objects (vals) {
      if (vals) {
        this.table.modelMain = []
        if (vals.length > 0) this.table.modelMain.push(vals[0])
        this.table.header.loading = false
        this.table.body.data = vals
      }
    },
    get_object (vals) { this.refreshData({}) }
  },
  beforeDestroy () {
    this.CLEAR_OBJECTS()
  }

}
</script>

<style>

</style>

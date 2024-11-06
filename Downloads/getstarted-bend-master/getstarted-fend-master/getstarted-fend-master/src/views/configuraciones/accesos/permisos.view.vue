<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols="12">
        <DataTableMain :datatable="table" :on-success="SuccessTableAction" :itemsAction="SuccessTableAction"/>
      </v-col>
    </v-row>
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
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'roles.permisos.create-update' }
        ],
        titles: [
          { text: 'Clave', value: 'slug', class: 'black--text' },
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Descripción', value: 'descripcion', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text', align: 'right' }
        ],
        loading: false
      },
      body: {
        data: [],
        actions: [
          { title: 'Editar', icon: 'mdi-pencil', color: 'warning', action: 'updateData', code: 'roles.permisos.create-update' }
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
    MainFormDialog
  },
  mounted () {
    this.refreshData({})
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
  },
  methods: {
    ...mapActions(['getUrlServices', 'GETObjectsService']),
    ...mapMutations(['SHOW_DIALOG_MAIN', 'CLEAR_OBJECTS']),
    /* *==========*==========*==========*==========*==========*==========*==========* */
    SuccessTableAction (item) {
      this[item.action](item)
    },
    refreshData (item) {
      this.table.header.loading = true
      this.getUrl('roles.permisos.show')
    },
    newData (item) {
      this.objectMain = {}
      this.paramsDialog.form = this.get_PermisosForm
      this.paramsDialog.url = this.get_urls['roles.permisos.create-update']
      this.paramsDialog.message = 'Registro Agregado Correctamente'
      this.SHOW_DIALOG_MAIN({ type: 'create', with: 850, title: 'Nuevo Permiso' })
    },
    updateData ({ item }) {
      this.objectMain = item
      this.paramsDialog.form = this.get_PermisosForm
      this.paramsDialog.url = this.get_urls['roles.permisos.create-update']
      this.paramsDialog.message = 'Registro Agregado Correctamente'
      this.paramsDialog.setmodel = true
      this.SHOW_DIALOG_MAIN({ type: 'update', with: 850, title: 'Editar Permiso' })
    },

    getUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.GETObjectsService({ url: router })
      } else {
        this.getUrlServices()
      }
    }
  },
  computed: {
    ...mapGetters(['get_dialogMain', 'get_urls', 'get_objects',
      'get_PermisosForm'])
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

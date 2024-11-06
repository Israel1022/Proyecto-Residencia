<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols="12">
        <DataTableMain :datatable="table" :on-success="successAction" :itemsAction="tableAction"/>
      </v-col>
      <v-col cols="12">
        <ViewDetails :object="object" type="ProcesosDetail" />
      </v-col>
    </v-row>
    <TipoProcesoDialog :model="permiso" v-if="get_dialogMain.dialog && (get_dialogMain.type == 'create' || get_dialogMain.type == 'update')" />
  </v-container>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
import DataTableMain from '@/components/tables/DataTableMain'
// import TipoProcesoDialog from '@/components/dialog/configuracion/TipoProcesoDialog'
import ViewDetails from '@/components/details/ViewDetailsMain'
export default {
  data: () => ({
    table: {
      modelMain: [],
      showSelect: true,
      singleSelect: true,
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData', code: 'libre', hidden: '' }
          // ,{ title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'libre', hidden: '' }
        ],
        titles: [
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          { text: 'Empresa', value: 'empresa.nombre', class: 'black--text' },
          { text: '', value: 'Actions', class: 'black--text' }
        ],
        loading: false,
        filter: true
      },
      body: {
        data: [],
        actions: [
          { title: 'Nuevo Proseso', icon: 'mdi-buffer', color: 'light-green darken-2', action: 'newProcessData' }
        ]
      },
      footer: { paging: true }
    },
    permiso: {}
  }),
  components: {
    DataTableMain,
    ViewDetails
  },
  mounted () {
    this.getUrl('tipo-proceso.procesos-list')
  },
  methods: {
    successAction (item) {
      this[item.action](item)
    },
    refreshData (item) {
      this.table.header.loading = true
      this.getUrl('tipo-proceso.procesos-list')
    },
    newData (item) {
      this.SHOW_DIALOG_MAIN({ type: 'create', with: 500, title: 'Nuevo Tipo Proceso' })
    },
    tableAction (item) {
      this[item.action](item)
    },
    newProcessData (item) {
    },

    ...mapActions(['getUrlServices', 'urlGetServicesActionList']),
    getUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.urlGetServicesActionList({ url: router, data: {} })
      } else {
        this.getUrlServices()
      }
    },
    ...mapMutations(['SHOW_DIALOG_MAIN', 'CLEAR_OBJECTS'])
  },
  computed: {
    ...mapGetters(['get_dialogMain', 'get_urls', 'get_objects']),
    object () {
      if (this.table.modelMain.length > 0) {
        return this.table.modelMain[0]
      }
      return {}
    }
  },
  watch: {
    get_urls () {
      this.getUrl('tipo-proceso.procesos-list')
    },
    get_objects (vals) {
      if (vals.length > 0) {
        this.table.modelMain = []
        this.table.modelMain.push(vals[0])
      }
      this.table.body.data = vals
      this.table.header.loading = false
    }
  },
  beforeDestroy () {
    this.CLEAR_OBJECTS()
  }

}
</script>

<style>

</style>

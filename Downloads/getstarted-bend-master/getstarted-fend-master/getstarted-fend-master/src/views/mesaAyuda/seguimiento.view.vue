<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols='12'>
        <DataTableServer
          :datatable='tableServer'
          :on-success='successAction'
          :itemsAction='successAction'
          :on-pagination="pagePagination"
          :on-search="getSearchPagePagination"
          :expresionSearch="exprecion"
        />

      </v-col>
      <v-col cols="12"> <ViewDetails :object="object" type="Solicitud" :actions="successAction" /> </v-col>
      <!-- <v-col cols='12' sm='6'>
        <ViewDetails :object="object" type="Movements" />
      </v-col>
       -->
    </v-row>
    <MainFormDialog v-if="get_dialogMain.dialog" :model="objectMain" :params="paramsDialog" :on-success="dataForm"  />
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
import XLSX from 'xlsx'
import { Permission } from '@/mixins/permissionMain'
import DataTableServer from '@/components/tables/DataTableMainServer'
import MainFormDialog from '@/components/dialog/main/MainFormDialog'
import ViewDetails from '@/components/ViewDetails'

export default {
  mixins: [Permission],
  data: () => ({
    tableServer: {
      modelMain: [],
      showSelect: true,
      singleSelect: true,
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData', pagination: {}, code: 'libre', hidden: '' },
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'solicitud.crear' },
          {
            title: 'Reporte',
            icon: 'mdi-cloud-download',
            color: 'light-blue lighten-1',
            code: 'ma.export-list',
            options: [
              { title: 'Reporte general', icon: 'mdi-clipboard-text', color: 'yellow darken-3', action: 'ExportTodos' }
            ]
          }
        ],
        titles: [
          { text: 'Folio', value: 'folio', class: 'black--text' },
          { text: 'Solicitante', value: 'SolicitanteSelected', class: 'black--text' },
          { text: 'Departamento', value: 'departamento.nombre', class: 'black--text' },
          { text: 'Documento', value: 'servicio', align: 'center', class: 'black--text' },
          { text: 'Estatus', value: 'Status', align: 'center', class: 'black--text' },
          { text: 'Fecha', value: 'created_at', align: 'center', class: 'black--text' },
          // { text: 'Activo', value: 'Activo', align: 'center', class: 'black--text' },
          { text: '', value: 'Actions', class: 'black--text', align: 'right' }
        ],
        loading: false,
        filter: true,
        filterCount: 0
      },
      body: {
        data: [],
        actions: [
          { title: 'Editar', icon: 'mdi-clipboard-edit', color: 'yellow darken-2', action: 'updateData', code: 'ma.solicitud.edit' },
          { title: 'Desactivar', icon: 'mdi-delete', color: 'red darken-2', action: 'toDisableEnable', code: 'mesa-ayuda.solicitud.disable-enable' }
        ],
        totalData: 0,
        pagination: {}
      },
      footer: { paging: true }
    },
    filterTableData: {},
    pages: {},
    pagesDefault: {
      groupBy: [],
      groupDesc: [],
      itemsPerPage: 10,
      multiSort: false,
      mustSort: false,
      page: 1,
      params: {},
      sortBy: [],
      sortDesc: []
    },
    item: {},
    exprecion: null,
    execute: false,
    usuarioIdField: { name: 'Usuarios', nameid: 'usuario_id', url: '/empresa/usuarios', cols: 12, rules: true },
    selectTipoActividadField: { name: 'Tipo Actividad', nameid: 'tipo_proceso_id', url: '/empresa/tipo-actividades/show-solicitudes', cols: 12, rules: true },
    selectServicioField: { name: 'Tipo Servicio', nameid: 'servicio', url: '/catalogo/tipos-atencion', value: 'id' },
    params: {},
    paramsDialog: {},
    objectMain: {}

  }),
  components: {
    MainFormDialog,
    DataTableServer,
    ViewDetails
    // MesaAyudaDialog,
    // MesaAyudaTicketsDialog,
    // MesaAyudaEditTicketsDialog,
    // SelectedMain
  },
  mounted () {
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.tableServer })
    // this.getDataUrl('ma.list')
    this.exprecion = this.$route.params.exprecion
  },
  methods: {
    ...mapActions(['getUrlServices', 'urlGetServicesActionList',
      'urlPostServicesActionPagination', 'GETObjectService', 'postExecuteAction', 'postUrlImportDataServerAction',
      'DELETEObjectServicePagination']),
    ...mapMutations(['SHOW_DIALOG_MAIN', 'UPDATE_MESA_AYUDA_SERVICES']),
    successAction (item) {
      this[item.action](item)
    },
    // *==========*==========*==========* Methods Table Pagination *==========*==========*==========* \\
    pagePagination (val) {
      this.tableServer.header.loading = true
      this.pages = val
      this.getDataUrl('mesa-ayuda.show-pagination')
    },
    getSearchPagePagination (val) {
      this.tableServer.header.loading = true
      this.pages = val
      this.getDataUrl('mesa-ayuda.show-pagination')
      // this.getDataUrl('ma.list-pagination-search')
      // this.pages = val
    },
    filterData (item) {
      this.objectMain = this.filterTableData
      this.paramsDialog = {
        form: this.get_MesaAyudaFilterForm,
        message: 'Registro Agregado Correctamente',
        returnObject: true,
        setmodel: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'filter', with: '60%', title: 'FILTRAR' })
    },
    ViewMovimiento ({ item }) {
      this.objectMain = { folio: item.folio }
      this.paramsDialog = {
        form: this.get_MovimientosFilterForm,
        message: 'Registro Agregado Correctamente'
      }
      this.SHOW_DIALOG_MAIN({ type: 'view', with: '65%', title: 'Movimientos' })
    },
    /* *==========*==========*==========*==========*==========* */
    /* *==========*==========*==========*==========*==========* */
    refreshData (item) {
      this.tableServer.header.loading = true
      this.pages = item.pagination
      this.getDataUrl('mesa-ayuda.show-pagination')
    },
    newData () {
      this.objectMain = { interno: false, equipo_inventario: false } // registrar_solicitante: false, registrar_equipo: false
      this.paramsDialog = {
        message: 'Registro Agregado Correctamente',
        showActividad: true,
        setmodel: true,
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'create', with: '65%', title: 'Nueva Solicitud' })
    },
    updateData ({ item }) {
      this.objectMain = item
      this.paramsDialog = {
        urloption: this.get_urls[item.tipo_actividad.permiso],
        url: this.get_urls[item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        showActividad: true,
        setmodel: true,
        updated: true,
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'edit', with: 850, title: `Editar Solicitud - ${item.folio}` })
    },
    toAssign (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'assign', form: 'process', with: '600', title: 'Asignar Servicio' })
    },
    toAttend (item) {
      console.log('item.item.tipo_actividad.accion', item.item.tipo_actividad.accion)
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'attend', form: 'process', with: '40%', title: 'Atender Servicio' })
    },
    toFinish (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'finish', form: 'process', with: '40%', title: 'Finalizar Servicio' })
    },

    toDiagnosis (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'diagnosis', form: 'process', with: '40%', title: 'Diagnósticar Equipo' })
    },
    toService (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'service', form: 'process', with: '60%', title: 'Reparación de Equipo' })
    },
    toExit (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'exit', form: 'process', with: '45%', title: 'Entregar Equipo' })
    },

    toExitInsumo (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'exitInsumo', form: 'process', with: '65%', title: 'Salida de Insumos' })
    },

    toCancel (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls[item.item.tipo_actividad.accion],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'cancel', form: 'process', with: '40%', title: `${item.body.status_finish.nombre} Servicio` })
    },

    toDeliveryInsumo (item) {
      this.objectMain = item
      this.paramsDialog = {
        url: this.get_urls['mesa-ayuda.execute'],
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'delivery', form: 'process', with: '45%', title: 'Entrega de Insumos' })
    },
    toDisableEnable ({ body, item }) {
      const isActive = (item.activo === 'si') ? 'Desactivar' : 'Activar'
      this.$swal.fire({
        title: `Estas seguro de ${isActive} el registro ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Si, ${isActive}!`,
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          const url = this.get_urls[body.code]
          this.DELETEObjectServicePagination({ url: url, params: item.id, replace: '{id}' })
        }
      })
    },
    PrintPdf ({ item }) {
      const router = this.get_urls['ma.pdf']
      this.objectMain = { ruta: router, data: { id: item.id } }
      this.paramsDialog = {
        form: this.get_PreviewPdfForm,
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'previewpdf', with: '90%', title: 'Servicio Pdf' })
    },
    PrintPdfInsumos ({ item }) {
      if (item.last_movement.estatus_id === 20) {
        const router = this.get_urls['ma.pdf-insumos']
        this.objectMain = { ruta: router, data: { id: item.id } }
        this.paramsDialog = {
          form: this.get_PreviewPdfForm,
          message: 'Registro Agregado Correctamente',
          returnObject: true
        }
        this.SHOW_DIALOG_MAIN({ type: 'previewpdf', with: '90%', title: 'Insumos Pdf' })
        return
      }
      this.$swal({
        type: 'warning',
        icon: 'warning',
        title: 'Advertencia !',
        text: 'El documento se debe imprimir cuando se haga la entrega de los insumos'
      })
    },
    PrintSolicitudPdf ({ item }) {
      const router = this.get_urls['ma.pdf-solicitud']
      this.objectMain = { ruta: router, data: { id: item.id } }
      this.paramsDialog = {
        form: this.get_PreviewPdfForm,
        message: 'Registro Agregado Correctamente',
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'previewpdf', with: '90%', title: 'Solicitud Pdf' })
    },

    toSendGeneralDirection (item) {
      this.item = item
      this.SHOW_DIALOG_MAIN({ type: 'central', with: 600, title: 'Enviar Equipo a Direccion General' })
    },

    dataForm ({ name, model }) {
      if (name === 'filter') {
        // console.log('model', model)
        this.filterTableData = model
        let count = 0
        this.get_MesaAyudaFilterForm.map(field => {
          if (model[field.nameid]) count += 1
        })
        console.log('count', count)
        this.tableServer.header.filterCount = count
        this.tableServer.header.loading = true

        if (this.pages) {
          if (this.pages.search) {
            this.pages.params = model
            this.getDataUrl('ma.list-pagination-search')
          } else {
            this.pages = this.pagesDefault
            this.pages.params = model
            this.getDataUrl('mesa-ayuda.show-pagination')
          }
        } else {
          this.pages = this.pagesDefault
          this.pages.params = model
          this.getDataUrl('mesa-ayuda.show-pagination')
        }
      } else if (name === 'exportData') {
        if (model) {
          const data = XLSX.utils.json_to_sheet(model.data)
          const workbook = XLSX.utils.book_new()
          const filename = model.name
          XLSX.utils.book_append_sheet(workbook, data, filename)
          XLSX.writeFile(workbook, `${filename}.xlsx`)
        }
      } else {
        this.refreshData({})
      }
    },
    // *==========*==========*==========*==========*==========*==========* \\
    getDataUrl (url) {
      if (this.get_urls != null) {
        const router = this.get_urls[url]
        this.urlPostServicesActionPagination({ url: router, data: this.pages })
      } else {
        this.getUrlServices()
      }
    },
    ExportTodos (item) {
      this.objectMain = {}
      this.paramsDialog = {
        form: this.get_MesaAyudaFilterReporteForm,
        url: this.get_urls['mesa-ayuda.reporte.general'],
        message: 'Registro Actualizado Correctamente',
        setmodel: true,
        returnObject: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'exportData', with: '50%', title: 'Reportes General' })
    },
    getUrlExport (objet) {
      const router = this.get_urls[objet]
      this.GETObjectService({ url: router, data: {} })
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_dialogMain', 'get_object', 'get_objectExport', 'get_objectPagination', 'get_reload',
      'get_refreshData',
      'get_PreviewPdfForm', 'get_MesaAyudaFilterForm', 'get_MesaAyudaFilterReporteForm', 'get_MovimientosFilterForm']),
    object () {
      if (this.tableServer.modelMain.length > 0) {
        return this.tableServer.modelMain[0]
      }
      return {}
    }
  },
  watch: {
    get_urls (val) {
      if (val) {
        this.getDataUrl('mesa-ayuda.show-pagination')
      }
    },
    get_object (vals) {
      if (vals) {
        const data = XLSX.utils.json_to_sheet(vals.data)
        const workbook = XLSX.utils.book_new()
        // const nameHours = this.getDateAndHour()
        const filename = vals.name
        XLSX.utils.book_append_sheet(workbook, data, filename)
        XLSX.writeFile(workbook, `${filename}.xlsx`)
      }
    },
    get_objectExport (val) {
      if (val.message) {
        this.$swal({
          type: 'error',
          icon: 'error',
          title: 'Error !',
          text: val.message
        })
        return
      }
      const linkSource = `data:application/pdf;base64,${val.pdf}`
      const downloadLink = document.createElement('a')
      downloadLink.href = linkSource
      downloadLink.download = val.nombre
      downloadLink.click()
    },
    get_objectPagination (val) {
      if (val) {
        this.tableServer.body.data = val.data
        this.tableServer.body.totalData = val.total
        if (val.data.length > 0) {
          this.tableServer.modelMain = []
          this.tableServer.modelMain.push(val.data[0])
        } else {
          this.tableServer.modelMain = []
        }
        this.tableServer.header.loading = false
      }
    },
    get_reload (val) {
      let findobject = -1
      this.tableServer.body.data.map((el, i) => {
        if (el.id === val.id) {
          findobject = i
        }
      })
      if (findobject !== -1) {
        Object.assign(this.tableServer.body.data[findobject], val)
      } else {
        // this.getDataUrl('mesa-ayuda.show-pagination')
      }
    },
    get_refreshData (val) {
      this.refreshData({})
    }
  }
}
</script>

<style></style>

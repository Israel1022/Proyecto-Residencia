<template>
  <v-container fluid>
    <v-row dense>
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
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'catalogo.caracteristicas.create-update' }
        ],
        titles: [
          { text: 'Codigo Area', value: 'nombre', class: 'black--text' },
          { text: 'Serie', value: 'tipo_dato.nombre', class: 'black--text' },
          { text: 'Subserie', value: 'DinamicObjectData', class: 'black--text' },
          { text: 'Area', value: 'Activo', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text', align: 'right' }
        ],
        loading: false
      },
      body: {
        data: [],
        actions: [
          { title: 'Activar / Desactivar', icon: 'mdi-checkbox-blank-circle', color: 'grey darken-3', action: 'disableData', code: 'catalogo.caracteristicas.up-down' },
          { title: 'Editar', icon: 'mdi-pencil', color: 'amber darken-3', action: 'updateData', code: 'catalogo.caracteristicas.create-update' }
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
    DataTableMain
  },
  mounted () {
    this.refreshData({})
  },
  methods: {
    ...mapActions(['getUrlServices', 'GETObjectsService', 'PUTDisableEnableObjectService']),
    ...mapMutations(['SHOW_MAIN_FORM_DIALOG', 'SHOW_MAIN_MESSAGE_DIALOG']),
    actionTable (item) {
      this[item.action](item)
    },
    /* *==========*==========*==========*==========*==========*==========*==========*==========* */
    refreshData (item) {
      // this.table.header.loading = true
      // this.ExecuteUrl('catalogo.caracteristicas.show')
    },
    newData () {
      this.SHOW_MAIN_FORM_DIALOG({ identity: 'create', with: 850, title: 'Nueva Característica', form_type: 'form', body: {} })
    },
    disableData  ({ item }) {
      const name = (item.activo === 'si') ? 'Desactivar' : 'Activar'
      this.SHOW_MAIN_MESSAGE_DIALOG({
        with: 600,
        title: `¿Estás seguro de ${name} el registro?`,
        subtitle: `Al ${name} es necesario solicitar permiso para revertir el cambio`,
        body: {
          showCancel: true,
          showComfirm: true,
          nameCancel: 'No, Cancelar',
          nameComfirm: `Sí, ${name}`
        },
        data: item
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
    },
  },
  computed: {
    ...mapGetters(['get_ConfirmData'])
  },
  watch: {
    get_ConfirmData (data) {
      if (identity === '') {

      } else {

      }
    }
  },
  beforeDestroy () {
    this.CLEAR_OBJECTS()
  }
}
</script>.

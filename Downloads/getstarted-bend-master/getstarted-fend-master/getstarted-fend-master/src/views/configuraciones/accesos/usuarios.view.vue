<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols="12">
        <DataTableMain :datatable="table" :on-success="successAction" :itemsAction="successAction"/>
      </v-col>
      <v-col cols="12">
        <ViewDetailsMain class="my-2" :object="objectDetail" type="UsuarioDetails" :actions="successAction" />
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
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'empresa.usuarios.store' }
        ],
        titles: [
          { text: 'Usuario', value: 'usuario', class: 'black--text' },
          { text: 'Empleado', value: 'empleado.NombreCompleto', class: 'black--text' },
          { text: 'Correo', value: 'email', class: 'black--text' },
          { text: 'Roles', value: 'RolesAsignados', class: 'black--text' },
          { text: 'Departamento', value: 'empleado.departamento.nombre', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          { text: '', value: 'ActionsGral', class: 'black--text', align: 'right' }
        ],
        loading: false
      },
      body: {
        data: [],
        actions: [
          { title: 'Editar', icon: 'mdi-pencil', color: 'warning', action: 'updateData', code: 'libre', hidden: 'empresa.usuarios.store' },
          { title: 'Asignar Rol', icon: 'mdi-account-network', color: 'warning', action: 'newDataRoles', code: 'empresa.usuarios.set-rol' },
          { title: 'Cambiar Contraseña', icon: 'mdi-account-key', color: 'info', action: 'updatePasswordData', code: 'empresa.usuarios.change-password' }
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
    ViewDetailsMain,
    MainFormDialog
  },
  mounted () {
    this.refreshData({})
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
  },
  methods: {
    ...mapActions(['GETObjectsService', 'POSTObjectsService', 'DELETEObjectService', 'getUrlServices']),
    ...mapMutations(['SHOW_DIALOG_MAIN', 'CLEAR_OBJECTS', 'SET_DATA_SECOUND_MAIN']),
    /* *==========*==========*==========*==========*==========*==========*==========* */
    successAction (item) {
      this[item.action](item)
    },
    refreshData (item) {
      this.table.header.loading = true
      this.ExecuteUrl('empresa.usuarios.show')
    },
    newData (item) {
      this.objectMain = {}
      this.paramsDialog.form = this.get_UsersForm
      this.paramsDialog.url = this.get_urls['empresa.usuarios.store']
      this.paramsDialog.message = 'Registro Agregado Correctamente'
      this.SHOW_DIALOG_MAIN({ type: 'createuser', with: 850, title: 'Nuevo Usuario' })
    },
    updateData ({ item }) {
      this.objectMain = item
      this.paramsDialog = {
        form: this.get_UsersUpdateForm,
        url: this.get_urls['empresa.usuarios.store'],
        message: 'Registro Agregado Correctamente',
        setmodel: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'createuser', with: 850, title: 'Actualizar Usuario' })
    },
    updatePasswordData ({ item }) {
      this.objectMain = { id: item.id }
      this.paramsDialog = {
        form: this.get_PasswordUpdateForm,
        url: this.get_urls['empresa.usuarios.change-password'],
        message: 'Registro Actualizado Correctamente',
        setmodel: true
      }
      this.SHOW_DIALOG_MAIN({ type: 'change-password', with: 850, title: `Cambiar Contraseña ( ${item.usuario} )` })
    },

    newDataRoles ({ item }) {
      this.objectMain = {
        id: item.id,
        usuario: item.usuario,
        NombreCompleto: item.empleado.NombreCompleto
      }
      this.paramsDialog.form = this.get_AssignRoles
      this.paramsDialog.url = this.get_urls['empresa.usuarios.set-rol']
      this.paramsDialog.message = 'Registro Agregado Correctamente'
      this.paramsDialog.setmodel = true
      this.SHOW_DIALOG_MAIN({ type: 'assignrol', with: 850, title: `Asignar Rol (${item.empleado.NombreCompleto})` })
    },
    AddDataDepto ({ item }) {
      this.table.header.loading = true
      const router = this.get_urls['empresa.usuarios.set-depto']
      this.POSTObjectsService({ url: router, data: item })
    },
    daleteDataRol ({ item, model }) {
      this.objectMain = {
        id: model.id,
        usuario: model.usuario,
        NombreCompleto: model.empleado.NombreCompleto,
        rol_id: item.id
      }
      this.paramsDialog.form = this.get_RemoveRol
      this.paramsDialog.url = this.get_urls['empresa.usuarios.set-rol']
      this.paramsDialog.message = 'Registro Eliminado Correctamente'
      this.paramsDialog.setmodel = true
      this.SHOW_DIALOG_MAIN({ type: 'assignrol', with: 850, title: 'Eliminar Rol' })
    },
    /* *==========*==========*==========*==========*==========*==========*==========* */
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
    ...mapGetters(['get_urls', 'get_dialogMain', 'get_objects', 'get_object',
      'get_UsersForm', 'get_UsersUpdateForm', 'get_AssignRoles', 'get_RemoveRol', 'get_PasswordUpdateForm']),
    objectDetail () {
      if (this.table.modelMain.length > 0) {
        return this.table.modelMain[0]
      }
      return {}
    }
  },
  watch: {
    get_urls () { this.refreshData({}) },
    get_objects (rows) {
      this.table.header.loading = true
      if (rows) {
        this.table.modelMain = []
        if (rows.length > 0) this.table.modelMain.push(rows[0])
        this.table.header.loading = false
        this.table.body.data = rows
      }
    },
    get_object (model) {
      this.table.modelMain = []
      this.$swal({
        type: 'success',
        icon: 'success',
        title: 'Existoso!!',
        text: 'Departamentos agregados correctamente',
        timer: 2000
      })
      let usuario = {}
      usuario = this.table.body.data.find(item => item.id === model.id)
      usuario.departamentos_asignados = model.departamentos_asignados

      this.table.modelMain.push(usuario)
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
crear curd de roles

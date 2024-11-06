<template>
  <v-card v-if="model.id" class="elevation-2" tile>
    <v-card-text>
      <v-row dense>
        <v-col cols="12">
          <p class="text-md-h4 text-sm-h5 text-center">Permisos del Rol</p>
        </v-col>
        <!--
        <v-col cols="3">
          <v-layout justify-end>
            <v-btn v-if="showMainPermiss('accesos.roles.set-permiso')" color="green darken-2" @click="sendPermission" dark>
            <v-icon left>mdi-check-outline</v-icon> Guardar Permisos
            </v-btn>
          </v-layout>
        </v-col>
        -->
        <v-col cols="12" sm="6" md="6">
          <v-card elevation="0">
            <v-card-text>
              <v-row dense>
                <v-col cols="12">
                  <DataTableMain :datatable="tablePermisos" :on-success="successAction" :items-action="tableAction" />
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <v-card elevation="0">
            <v-card-text>
              <v-row dense>
                <v-col cols="12">
                  <DataTableMain :datatable="table" :on-success="successAction" :items-action="tableAction" />
                </v-col>
                <!-- <v-col cols="12" class="scroll-box">
                  <div cols="12" v-for="(item, i) in selection" :key="i"> {{ item.nombre }} - ( {{ item.descripcion }} ) </div>
                </v-col> -->
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>

import { mapGetters, mapActions } from 'vuex'
import { Permission } from '@/mixins/permissionMain'
import DataTableMain from '@/components/tables/DataTableMain'
export default {
  mixins: [Permission],
  props: ['model', 'onSuccess'],
  name: 'RolPermisosDetails',
  data: () => ({
    table: {
      header: {
        options: [
          { title: 'Guardar Permisos', icon: 'mdi-check-outline', color: 'success', action: 'sendPermission', code: 'accesos.roles.set-permiso', local: true }
        ],
        titles: [
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Clave', value: 'slug', class: 'black--text' }
          // ,{ text: 'Acceso', value: 'descripcion', class: 'black--text' }
        ],
        loading: false,
        showSearch: true
      },
      body: {
        data: [],
        actions: [
          { title: 'Eliminar', icon: 'mdi-delete', color: 'error', action: 'daleteDataRol', code: 'libre', hidden: '' }
        ]
      },
      footer: {
        paging: true
      }
    },
    tablePermisos: {
      modelMain: [],
      showSelect: true,
      singleSelect: false,
      header: {
        options: [],
        titles: [
          { text: 'Clave', value: 'slug', class: 'black--text' },
          { text: 'Nombre', value: 'nombre', class: 'black--text' }
          // ,{ text: 'Acceso', value: 'descripcion', class: 'black--text' }
        ],
        loading: false,
        filter: true
      },
      body: {
        data: [],
        actions: [
          { title: 'Eliminar', icon: 'mdi-delete', color: 'error', action: 'daleteDataRol', code: 'libre', hidden: '' }
        ]
      },
      footer: {
        paging: true
      }
    },
    params: '?id=ENABLE',
    isPermiss: false
  }),
  components: {
    DataTableMain
  },
  mounted () {
    this.ShowPermisos({ permisos: JSON.parse(localStorage.getItem('permisos')), tableMain: this.table })
    // this.isPermiss = this.showMainPermiss('accesos.roles.set-permiso')
  },
  methods: {
    ...mapActions(['GETObjectsServiceTwo']),
    showMainPermiss (permiso) {
      return this.ReadOnlyPermiso(JSON.parse(localStorage.getItem('permisos')), permiso)
    },
    sendPermission () {
      const item = { action: 'permissionsAssign', item: { rol_id: this.model.id, permisos: this.tablePermisos.modelMain } }
      // this.selection
      this.onSuccess(item)
    },
    successAction (item) {
      if (item.local) {
        this[item.action]()
      } else {
        item.item = this.model
        this.onSuccess(item)
      }
    },
    tableAction (item) {
      item.model = this.model
      this.onSuccess(item)
    },
    // ==========*==========*==========*==========*========== \\
    ExecuteUrlTwo (objet) {
      const router = this.get_urls[objet]
      this.GETObjectsServiceTwo({ url: router, params: this.params })
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_object', 'get_objectsTwo']),
    selection () {
      return this.tablePermisos.modelMain
    }
  },
  watch: {
    model (row) {
      this.table.body.data = []
      if (row.permisos) {
        this.table.body.data = []
        this.tablePermisos.modelMain = row.permisos
        this.table.body.data = row.permisos
        this.ExecuteUrlTwo('roles.permisos.show')
      }
    },
    get_objectsTwo (row) {
      this.tablePermisos.body.data = []
      this.tablePermisos.body.data = row
    },
    selection (rows) {
      this.table.body.data = []
      this.table.body.data = rows
    }
  }
}
</script>
<style scoped>
.scroll-box {
  max-height: 750px;
  overflow-x: auto;
}
.scroll-box::-webkit-scrollbar {
  width: 10px;/* width of the entire scrollbar */
}

.scroll-box::-webkit-scrollbar-track {
  background: #d2d2d2;/* color of the tracking area */
  border-radius: 20px;
}
.scroll-box::-webkit-scrollbar-thumb {
  background-color: #3F51B5;  /* color of the scroll thumb */
  border-radius: 20px;       /* roundness of the scroll thumb */
  border: 3px solid #d2d2d2;/* creates padding around scroll thumb */
}
</style>

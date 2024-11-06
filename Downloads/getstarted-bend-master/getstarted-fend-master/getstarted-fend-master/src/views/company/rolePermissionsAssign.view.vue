<template>
  <v-container fluid>
      <v-layout justify-center>
          <v-flex md8>
              <v-card title>
                  <v-card-title>
                      <h2>Asignar Permisos a Rol : {{ rol.nombre }}</h2>
                      <v-spacer></v-spacer>
                      <v-btn icon dark color="red" @click="close">
                          <v-icon>mdi-close</v-icon>
                      </v-btn>
                  </v-card-title>
                  <v-card-text>
                    <v-col cols="12">
                    <v-radio-group v-model="action.special" row  @change="accessGlobal">
                      <v-radio label="Acceso total" value="all-access"></v-radio>
                      <v-radio label="sin Acceso" value="no-access"></v-radio>
                      <v-radio label="Manual" value=" "></v-radio>
                    </v-radio-group>
                    </v-col>
                    <v-simple-table dense>
                        <template v-slot:default>
                            <thead>
                              <tr>
                                <th class="text-left">Clave</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left"></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(item,index) in table.body.data" :key="index">
                                <td>{{ item.slug }}</td>
                                <td>{{ item.nombre }}</td>
                                <td class="switchAlig">
                                    <v-switch
                                        v-model="item.selected"
                                        label="Activo"
                                        color="success"
                                        hide-details
                                        class="switch"
                                        @change="setpermissionsAssign(item)">
                                    </v-switch>
                                </td>
                              </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                  </v-card-text>
              </v-card>
          </v-flex>
      </v-layout>
  </v-container>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
import router from '@/router'
export default {
  data: () => ({
    table: {
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData' },
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData' }
        ],
        titles: [
          { text: 'Id', value: 'id', class: 'black--text' },
          { text: 'Nombre', value: 'nombre', class: 'black--text' },
          { text: 'Usuario', value: 'usuario', class: 'black--text' },
          { text: 'Email', value: 'email', class: 'black--text' },
          { text: 'Activo', value: 'Activo', class: 'black--text' },
          { text: 'Fecha Creacion', value: 'Created', class: 'black--text' },
          { text: 'Fecha Actualizada', value: 'Updated' },
          { text: '', value: 'Actions', class: 'black--text' }
        ],
        loading: false
      },
      body: {
        data: [],
        actions: []
      },
      footer: {
        paging: true
      }
    },
    action: {
      rol_id: 0,
      check: false,
      permiso_id: 0,
      special: ' '
    },
    permisos: [],
    rol: {}
  }),
  components: {},
  mounted () {
    if (this.$route.params.id) {
      this.action.rol_id = this.$route.params.id
    }
    this.getUrl('empresa.permisos-list')
  },
  methods: {
    successAction (item) {
      this[item.action](item)
    },
    tableAction (item) {
      this[item.action](item)
    },
    accessGlobal () {
      this.action.check = true
      this.saveActionPermission('empresa.roles-set')
    },
    setpermissionsAssign (item) {
      this.action.permiso_id = item.id
      this.action.check = item.selected
      this.saveActionPermission('empresa.roles-set')
    },
    ...mapActions(['getUrlServices', 'postServiceListAction', 'urlPostServicesAction', 'urlPostServicesActionSecound']),
    getUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.postServiceListAction({ url: router, data: {} })
      } else {
        this.getUrlServices()
      }
    },
    getUrlRole (objet) {
      const router = this.get_urls[objet]
      this.urlPostServicesActionSecound({ url: router, data: { id: this.action.rol_id } })
    },
    saveActionPermission (objet) {
      const router = this.get_urls[objet]
      this.urlPostServicesAction({ url: router, data: this.action })
    },
    close () {
      router.push({ name: 'ViewRols' })
    },
    ...mapMutations(['SHOW_DIALOG_MAIN', 'CLEAR_OBJECTS'])
  },
  computed: {
    ...mapGetters(['get_dialogMain', 'get_urls', 'get_objects', 'get_objectTwo'])
  },
  watch: {
    get_urls () {
      this.getUrl('empresa.permisos-list')
    },
    get_objects (vals) {
      if (vals) {
        this.table.header.loading = false
        this.permisos = vals
        this.getUrlRole('empresa.roles-list')
      }
    },
    get_objectTwo (val) {
      if (val) {
        this.rol = val[0]
        // const permisos = val[0].permisos
        this.permisos.map(i => {
          this.rol.permisos.map(j => {
            if (i.slug === j.slug) {
              i.selected = true
            }
          })
        })
        this.table.body.data = this.permisos
      }
    }
  },
  beforeDestroy () {
    this.CLEAR_OBJECTS()
  }

}
</script>

<style>
.switch{
    margin: 0 auto;
}
</style>

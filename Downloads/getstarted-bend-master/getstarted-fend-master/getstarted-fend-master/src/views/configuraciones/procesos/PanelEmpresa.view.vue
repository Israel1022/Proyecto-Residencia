<template>
  <v-container fluid>
    <v-row dense>
      <v-col cols="12" md="6">
        <v-card outlined tile>
          <v-card-title>Importacion de Empleados</v-card-title>
          <v-card-text>
            <v-row dense>
              <v-col cols="12">
                <v-file-input v-model="chosenFile" show-size small-chips label="Cargar Arhivo" @change="setFile" outlined dense></v-file-input>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-row dense justify="end" class="pa-1">
              <v-btn color="green darken-2" @click="ProcessEmpleado" dark large>
                <v-icon left >mdi-cog</v-icon> <span class="subtitle-1 font-weight-bold">Ejecutar</span>
              </v-btn>
            </v-row>
          </v-card-actions>
          <v-progress-linear v-if="ImportacionEmpleadosLoadding" color="success" height="10" indeterminate striped></v-progress-linear>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
export default {
  data: () => ({
    ImportacionEmpleadosLoadding: false,
    params: {},
    urlMain: null,
    chosenFile: null
  }),
  mounted () {
  },
  methods: {
    ...mapActions(['getUrlServices', 'POSTImportDataServerAction']),
    ...mapMutations([]),
    setFile (file) {
      this.params = file
    },
    ProcessEmpleado () {
      this.ImportacionEmpleadosLoadding = true
      this.ExecuteUrl('empresa.importaciones.empleados.store')
    },
    ExecuteUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.POSTImportDataServerAction({ url: router, data: this.params })
        this.urlMain = null
      } else {
        this.urlMain = objet
        this.getUrlServices()
      }
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_objectImport'])
  },
  watch: {
    get_urls () {
      this.ExecuteUrl(this.urlMain)
    },
    get_objectImport (val) {
      if (val.message) {
        this.$swal({
          type: 'error',
          icon: 'error',
          title: 'Error!',
          text: val.message
        })
        return
      }
      this.$swal({
        type: 'success',
        icon: 'success',
        title: 'Exitos!',
        text: 'Importacion exitosa'
      })
      this.ImportacionEmpleadosLoadding = false
    }
  }
}
</script>

<style scoped>
</style>

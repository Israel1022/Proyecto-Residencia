<template>
  <v-card v-if="model.id" class="elevation-2">
    <v-card-title class="text-h6">
      <span class="font-weight-bold text--primary">Proceso de {{model.nombre}}</span>
      <v-spacer></v-spacer>
    </v-card-title>
    <v-card-text>
        <v-row dense>
            <v-col cols="3" v-for="(proceso, index) in model.procesos" :key="index">
                <v-card class="mx-auto" dark>
                    <v-card-text class="font-weight-light">
                        <v-row dense>
                            <v-col cols="12">
                                <span class="font-weight-bold"> Accion: </span>{{proceso.accion}}
                            </v-col>
                            <v-col cols="12">
                                <span class="font-weight-bold"> Permiso: </span>{{proceso.permiso}}
                            </v-col>
                            <v-col cols="6">
                                <p class="font-weight-bold">Estatus Inicial:</p>{{proceso.status_start.estatus}}
                            </v-col>
                            <v-col cols="6">
                                <p class="font-weight-bold">Estatus Final:</p>{{proceso.status_finish.estatus}}
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-card-actions>
                        <v-list-item class="grow">
                            <v-row align="center" justify="end" dense>
                                <v-btn class="mx-2" color="primary" small dark fab @click="notificarProcess(proceso)">
                                    <v-icon dark v-if="proceso.notificar"> mdi-bell-ring </v-icon>
                                    <v-icon dark v-else> mdi-bell-off </v-icon>
                                </v-btn>
                            </v-row>
                        </v-list-item>
                    </v-card-actions>

                </v-card>
            </v-col>
        </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from 'vuex'
export default {
  props: ['model'],
  name: 'ProcesosDetail',
  data: () => ({
    itemInsumos: {}
  }),
  components: {},
  methods: {
    ...mapActions(['urlPostServicesAction']),
    notificarProcess (proceso) {
      const message = !proceso.notificar ? 'Activar Notificacion' : 'Desactivar Notificacion'
      this.$swal.fire({
        title: `Estas seguro de ${message} al proceso?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Si, ${message}!`
      }).then((result) => {
        if (result.value) {
          const router = this.get_urls['proceso.notificacion']
          this.urlPostServicesAction({ url: router, data: { id: proceso.id, notificar: !proceso.notificar } })
        }
      })
    },
    ...mapMutations([])
  },
  computed: {
    ...mapGetters(['get_urls', 'get_object'])
  },
  watch: {
    get_object (val) {
      let findobject = -1
      this.model.procesos.map((el, i) => {
        if (el.id === val.id) {
          findobject = i
        }
      })
      if (findobject !== -1) {
        Object.assign(this.model.procesos[findobject], val)
      }
    }
  }
}
</script>

<style>

</style>

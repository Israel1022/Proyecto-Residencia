<template>
  <v-card v-if="model.id" class="elevation-2">
    <v-card-title class="text-h5">
      <h2 class="font-weight-bold text--primary"> Solicitud [model.tipo_actividad.nombre] </h2>
    </v-card-title>
    <v-card-subtitle class="title text--black font-weight-black font-weight-bold">
      [model.folio]  -
      <span v-if="model.descripcion.solicitante_id"> [model.descripcion.solicitante.NombreCompleto] </span>
      <span v-else >[model.descripcion.solicitante]</span>
    </v-card-subtitle>
    <v-tabs v-model="tab" background-color="primary" align-with-title dark>
      <v-tabs-slider color="white"></v-tabs-slider>
      <v-tab href="#one">General</v-tab>
      <v-tab href="#two" @click="viewEquipo()">Equipo Computo</v-tab>
      <v-tab href="#three" @click="viewMovs(model.folio)">Bitácora de Movimientos</v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
        <!-- General -->
        <v-tab-item value="one">
          <v-card width="auto" outlined>
            <v-card-text>
              <v-row dense>
              </v-row>
              <v-row>
              </v-row>
            </v-card-text>
          </v-card>
        </v-tab-item>
  
        <!-- Inventario -->
        <v-tab-item value="two">
          <v-card width="auto" outlined>
            <v-card-text>
            </v-card-text>
          </v-card>
        </v-tab-item>
  
        <!-- Bitacorra -->
        <v-tab-item value="three">
          <v-card width="auto" outlined>
            <v-card-text>
              <v-row dense>
                <v-col v-if="showMovs" cols="12">
                  <ViewMovementsField :itemAction="movs" />
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-tab-item>
    </v-tabs-items>
  </v-card>
</template>
  
<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex'
  import ViewMovementsField from '@/components/fields/specific/ViewMovementsField'
  export default {
    props: ['model', 'onSuccess'],
    data: () => ({
      tab: 0,
      movs: {
        field: 'showMovsDataServer',
        name: '',
        nameid: 'movimientos',
        url: '/procesos/movimientos',
        default: '/procesos/movimientos',
        hidden: true,
        cols: 12
      },
      familia: null,
      showMovs: false,
      showEq: false
    }),
    components: {
      ViewMovementsField
    },
    methods: {
      ...mapActions(['urlGetServiceActionTwo']),
      ...mapMutations([]),
      successAction (item) {
        this.onSuccess(item)
      },
      viewMovs (folio) {
        /*
        this.showMovs = false
        this.movs.url = `${this.movs.default}?folio=${folio}&mov=all`
        setTimeout(() => { this.showMovs = true }, 200)
        */
      },
      viewEquipo () {
        /*
        if (this.model.descripcion.equipo) return
        this.showEq = false
        const router = this.get_urls['catalogo.familias-caracteristica.formulario']
        this.urlGetServiceActionTwo({ url: router, params: `/${this.model.descripcion.inventario.caracteristicas.familia_id}`, replace: '/{id}' })
        setTimeout(() => { this.showEq = true }, 200)
        */
      }
    },
    computed: {
      ...mapGetters(['get_urls', 'get_objectTwo'])
    },
    watch: {
      model (val) {
        this.tab = 'one'
      },
      get_objectTwo (val) {
        if (val) {
          this.familia = val
        }
      }
    }
  }
  </script>
  <style>
  </style>
  
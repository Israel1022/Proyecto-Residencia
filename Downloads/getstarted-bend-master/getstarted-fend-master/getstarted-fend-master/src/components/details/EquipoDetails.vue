<template>
  <v-card v-if="model && model.id" width="100%" tile elevation="0">
    <v-tabs v-model="tab" background-color="primary" dark>
      <v-tab>General</v-tab>
      <v-tab>Especificación</v-tab>
      <v-tab @click="viewMovs(model.folio)">Bitácora de Movimientos</v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <!-- General -->
      <v-tab-item>
        <v-card width="auto" outlined>
            <v-card-text>
              <v-row dense>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Folio: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.folio}}
                      </p>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Tipo Inventario: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.tipo_inventario.nombre}}
                      </p>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Categoria: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.caracteristicas.familia.nombre}}
                      </p>
                  </v-col>

                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Nombre del Equipo: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.caracteristicas.descripcion}}
                      </p>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Marca - Modelo: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.MarcaModelo}}
                      </p>
                  </v-col>
              </v-row>
              <v-row dense v-if="model.tipo_inventario_id !== 1">
                  <v-col cols="12" sm="6">
                      <label class="font-weight-bold subtitle-1 text--primary">Personal Resguardante: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.PersonalResguardante}}
                      </p>
                  </v-col>
                  <v-col cols="12" sm="6">
                      <label class="font-weight-bold subtitle-1 text--primary">Personal Asignado: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.PersonalAsignado}}
                      </p>
                  </v-col>
              </v-row>
            </v-card-text>
        </v-card>
      </v-tab-item>

      <!-- Especificación -->
      <v-tab-item>
        <v-card width="auto" outlined>
          <v-card-text>
            <v-row dense v-if="familia">
                <v-col v-for="(field, index) in familia.fields" :key="index" :cols="field.cols" sm="6">
                    <label class="font-weight-bold subtitle-1 text--primary">{{field.name}} </label>
                    <p class="font-weight-bold text--secondary">
                        {{model.especificaciones[field.nameid]}}
                    </p>
                </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-tab-item>
      <!-- Bitacorra -->
      <v-tab-item>
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
  name: 'EquipoDetails',
  data: () => ({
    tab: 0,
    itemInsumos: {},
    familia: null,
    movs: {
      field: 'showMovsDataServer',
      name: '',
      nameid: 'movimientos',
      url: '/procesos/movimientos',
      default: '/procesos/movimientos',
      hidden: true,
      cols: 12
    },
    showMovs: true
  }),
  components: {
    ViewMovementsField
  },
  methods: {
    ...mapActions(['urlGetServiceActionTwo']),
    ...mapMutations([]),
    sendEvent (item) {
      this.onSuccess(item)
    },
    viewMovs (folio) {
      this.showMovs = false
      this.movs.url = `${this.movs.default}?folio=${folio}&mov=all`
      setTimeout(() => { this.showMovs = true }, 200)
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_objectTwo'])
  },
  watch: {
    model (val) {
      this.tab = 0
      if (val.id) {
        const router = this.get_urls['catalogo.familias-caracteristica.formulario']
        this.urlGetServiceActionTwo({ url: router, params: `/${val.caracteristicas.familia_id}`, replace: '/{id}' })
      }
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

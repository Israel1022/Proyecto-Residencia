<template>
  <v-card v-if="model.id" class="elevation-2">
    <v-card-title class="text-h5">
      <h2 class="font-weight-bold text--primary"> Solicitud ({{ model.tipo_actividad.nombre }}) </h2>
        <!--
        <span v-if="model.last_movement.estatus.formulario.actions">
            <span v-if="model.last_movement.estatus.formulario.actions.length == 1 ">
                <v-btn v-for="(action, index) in model.last_movement.estatus.formulario.actions" :key="index"
                    small :color="action.color" dark class="mr-1"
                    @click="successAction({ action:action.action, item: model, body:action})"> {{ action.title }}
                    <v-icon right dark v-if="action.icon">{{ action.icon }}</v-icon>
                </v-btn>
            </span>
            <v-menu offset-y v-else>
                <template v-slot:activator="{ attrs, on }">
                    <v-btn color="primary" class="ma-1" v-bind="attrs" v-on="on" small>Opciones</v-btn>
                </template>
                <v-list dense>
                    <v-list-item v-for="(action, index) in model.last_movement.estatus.formulario.actions" :key="index" link
                        @click="successAction({action:action.action, item: model, body:action})">
                        <v-list-item-title v-text="action.title" />
                    </v-list-item>
                </v-list>
            </v-menu>
        </span>
        -->
    </v-card-title>
    <v-card-subtitle class="title text--black font-weight-black font-weight-bold">
      {{model.folio}} -
      <span v-if="model.descripcion.solicitante_id"> {{model.descripcion.solicitante.NombreCompleto}} </span>
      <span v-else >{{model.descripcion.solicitante}}</span>
    </v-card-subtitle>

    <v-tabs v-model="tab" background-color="primary" align-with-title dark>
      <v-tabs-slider color="white"></v-tabs-slider>
      <v-tab href="#one">General</v-tab>
      <v-tab href="#three" @click="viewMovs(model.folio)">Bitácora de Movimientos</v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <!-- General -->
      <v-tab-item value="one">
        <v-card width="auto" outlined>
          <v-card-text>
            <v-row dense>
              <v-col cols="12" sm="4" md="3">
                <label class="font-weight-bold subtitle-1 text--primary">Asignado: </label>
                <p class="font-weight-bold text--secondary">
                  <v-chip v-if="model.Asignado && model.Asignado.detalle"
                    class="my-1" outlined label :color="model.Asignado.estatus.color">
                      {{model.Asignado.detalle.descripcion.empleado.NombreCompleto}}
                  </v-chip>
                  <v-chip v-else class="my-1" outlined label color="info">NO ASIGNADO</v-chip>
                </p>
              </v-col>
              <v-col cols="12" sm="4" md="3">
                <label class="font-weight-bold subtitle-1 text--primary">Tipo de Atención: </label>
                <p class="font-weight-bold text--secondary">
                    <v-chip class="my-2" color="primary" outlined label> {{model.tipo_servicio.nombre}}</v-chip>
                </p>
              </v-col>
              <v-col cols="6" sm="3" md="3">
                <label class="font-weight-bold subtitle-1 text--primary">Tipo de Actividad: </label>
                <p class="font-weight-bold text--secondary">
                  <v-chip class="my-1" outlined label :color="model.tipo_actividad.color">
                    {{model.tipo_actividad.nombre}}
                  </v-chip>
                </p>
              </v-col>
              <v-col cols="12" sm="4" md="3">
                <label class="font-weight-bold subtitle-1 text--primary">Estatus: </label>
                <p class="font-weight-bold text--secondary">
                    <v-chip class="my-1" outlined label
                        :color="model.last_movement.estatus.color">
                      {{model.last_movement.estatus.estatus}}
                    </v-chip>
                </p>
              </v-col>
            </v-row>

            <v-row v-if="model.descripcion.programar" dense>
              <v-col cols="12" sm="4">
                <label class="font-weight-bold subtitle-1 text--primary">Tiempo Programado: </label>
                <p class="font-weight-bold text--secondary">
                  <v-chip v-if="model.descripcion.programar" class="my-2 text-uppercase" color="success" outlined label>
                    {{model.descripcion.programar}}
                  </v-chip>
                </p>
              </v-col>
              <v-col cols="12" sm="6" v-if="model.descripcion.programar == 'Programado'">
                  <label class="font-weight-bold subtitle-1 text--primary">Fecha Programada: </label>
                  <p class="font-weight-bold text--secondary ma-0">
                      <span class="text--primary">Fecha Ingreso:</span> {{model.descripcion.fInicial}} -
                      <span class="text--primary"> Fecha Entrega:</span> {{model.descripcion.fFinal}}
                  </p>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12"></v-col>
              <v-col cols="12" sm="6" md="6">
                <label class="font-weight-bold subtitle-1 text--primary">Correo: </label>
                <p v-if="model.descripcion.correo" class="font-weight-bold text--secondary">
                    {{ model.descripcion.correo }}
                </p>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <label class="font-weight-bold subtitle-1 text--primary">Telefono: </label>
                <p class="font-weight-bold text--secondary">
                    {{ model.descripcion.telExt }}
                </p>
              </v-col>

              <v-col cols="12" sm="4" md="6">
                <label class="font-weight-bold subtitle-1 text--primary">Reporte : </label>
                <p class="font-weight-bold text--secondary">
                  {{ model.descripcion.reporte }}
                </p>
              </v-col>
              <v-col cols="12" sm="4" md="6" v-if="model.descripcion.direccion_id || model.descripcion.calle">
                <label class="font-weight-bold subtitle-1 text--primary">Direccion: </label>
                <p v-if="model.descripcion.direccion_id" class="font-weight-bold text--secondary">
                  {{ model.descripcion.direccion.contacto }}
                </p>
                <p v-else-if="model.descripcion.calle" class="font-weight-bold text--secondary">
                  Calle {{ model.descripcion.calle }} #{{ model.descripcion.numero }} {{ model.descripcion.cruzamiento }}, {{ model.descripcion.colonia }}
                </p>
              </v-col>
              <v-col cols="12">
                <label class="font-weight-bold subtitle-1 text--primary">Observaciones: </label>
                <p class="font-weight-bold text--secondary">
                    {{model.descripcion.observaciones}}
                </p>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-tab-item>

      <!-- Inventario -->
      <v-tab-item value="two">
        <v-card width="auto" outlined>
          <v-card-text>
            <v-row dense v-if="model.descripcion.inventario_id">
              <v-col cols="12" sm="6">
                <p class="text-h5 black--text font-weight-bold">General</p>
                <v-row dense>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Folio: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.descripcion.inventario.folio}}
                      </p>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Tipo Inventario: </label>
                      <p class="font-weight-bold text--secondary" v-if="model.descripcion.inventario.tipo_inventario">
                          {{model.descripcion.inventario.tipo_inventario.nombre}}
                      </p>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Categoria: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.descripcion.inventario.caracteristicas.familia.nombre}}
                      </p>
                  </v-col>

                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Nombre del Equipo: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.descripcion.inventario.caracteristicas.descripcion}}
                      </p>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                      <label class="font-weight-bold subtitle-1 text--primary">Marca - Modelo: </label>
                      <p class="font-weight-bold text--secondary">
                          {{model.descripcion.inventario.MarcaModelo}}
                      </p>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" sm="6">
                <p class="text-h5 black--text font-weight-bold">Especificación</p>
                <v-row dense v-if="familia">
                  <v-col v-for="(field, index) in familia.fields" :key="index" :cols="field.cols" sm="6">
                    <label class="font-weight-bold subtitle-1 text--primary">{{field.name}} </label>
                      <p class="font-weight-bold text--secondary">
                            {{model.descripcion.inventario.especificaciones[field.nameid]}}
                    </p>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
            <v-row dense v-else-if="model.descripcion.equipo">
              <v-col cols="6" sm="6" md="4">
                  <label class="font-weight-bold subtitle-1 text--primary">Equipo: </label>
                  <p class="font-weight-bold text--secondary">
                      {{model.descripcion.equipo}}
                  </p>
              </v-col>
              <v-col cols="6" sm="6" md="4">
                  <label class="font-weight-bold subtitle-1 text--primary">Marca: </label>
                  <p class="font-weight-bold text--secondary">
                      {{model.descripcion.marca.nombre}}
                  </p>
              </v-col>
              <v-col cols="6" sm="6" md="4">
                  <label class="font-weight-bold subtitle-1 text--primary">Serie: </label>
                  <p class="font-weight-bold text--secondary">
                      {{model.descripcion.serie}}
                  </p>
              </v-col>
              <v-col cols="6" sm="6">
                  <label class="font-weight-bold subtitle-1 text--primary">Familia: </label>
                  <p class="font-weight-bold text--secondary">
                      {{model.descripcion.familia.nombre}}
                  </p>
              </v-col>
              <v-col cols="6" sm="6">
                  <label class="font-weight-bold subtitle-1 text--primary">Departamento: </label>
                  <p class="font-weight-bold text--secondary">
                      {{model.descripcion.departamento_equipo.nombre}}
                  </p>
              </v-col>
            </v-row>
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
      this.showMovs = false
      this.movs.url = `${this.movs.default}?folio=${folio}&mov=all`
      setTimeout(() => { this.showMovs = true }, 200)
    },
    viewEquipo () {
      if (this.model.descripcion.equipo) return
      this.showEq = false
      const router = this.get_urls['catalogo.familias-caracteristica.formulario']
      this.urlGetServiceActionTwo({ url: router, params: `/${this.model.descripcion.inventario.caracteristicas.familia_id}`, replace: '/{id}' })
      setTimeout(() => { this.showEq = true }, 200)
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

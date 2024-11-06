<template>
  <div>
    <v-toolbar flat color="white" class="elevation-2">
      <v-toolbar-items class="hidden-sm-and-down">
          <span v-for="(item, index) in datatable.header.options" :key="index" class="pt-4">
            <span v-if="item.hidden == ''">
              <v-btn v-if="item.action" small :color="item.color" dark class="mr-1" @click="action(item)">
                  {{ item.title }}
                  <v-icon right dark v-if="item.icon">{{ item.icon }}</v-icon>
              </v-btn>
              <v-menu v-else offset-y>
                  <template v-slot:activator="{ attrs, on }">
                      <v-btn color="teal darken-1" class="white--text" v-bind="attrs" v-on="on" small>
                          {{item.title}} <v-icon right dark>{{item.icon}}</v-icon>
                      </v-btn>
                  </template>
                  <v-list dense>
                      <v-list-item v-for="(option, index) in item.options" :key="index" @click="action(option)" link dense>
                          <v-list-item-icon>
                            <v-icon>{{option.icon}}</v-icon>
                          </v-list-item-icon>
                          <v-list-item-subtitle>{{ option.title }}</v-list-item-subtitle>
                      </v-list-item>
                  </v-list>
              </v-menu>
            </span>
          </span>
      </v-toolbar-items>
      <span class="hidden-md-and-up">
        <v-menu>
            <template v-slot:activator="{ attrs, on }">
              <v-app-bar-nav-icon v-bind="attrs" v-on="on" small></v-app-bar-nav-icon>
              <!--
                <v-btn color="teal darken-1" class="white--text"
                    v-bind="attrs" v-on="on" small> <v-icon dark>mdi-cog</v-icon>
                </v-btn>-->
            </template>
            <v-list dense>
              <span v-for="(item, index) in datatable.header.options" :key="index">
                <span v-if="item.hidden == ''">
                  <v-list-item v-if="item.action" @click="action(item)" link dense>
                      <v-list-item-icon>
                        <v-icon>{{item.icon}}</v-icon>
                      </v-list-item-icon>
                      <v-list-item-subtitle>{{ item.title }}</v-list-item-subtitle>
                  </v-list-item>
                  <v-list-group v-else :value="true" no-action sub-group>
                    <template v-slot:activator>
                      <v-list-item-content>
                        <v-list-item-title>{{item.title}} <v-icon right dark>{{item.icon}}</v-icon></v-list-item-title>
                      </v-list-item-content>
                    </template>
                    <v-list-item v-for="(option, index) in item.options" :key="index" @click="action(option)" link dense>
                        <v-list-item-icon>
                          <v-icon>{{option.icon}}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-subtitle>{{ option.title }}</v-list-item-subtitle>
                    </v-list-item>

                  </v-list-group>
                </span>
              </span>
            </v-list>
        </v-menu>
      </span>
      <v-spacer />
      <v-text-field v-model="buscar" label="Buscar" class="inputSearch" clearable outlined dense />

      <v-badge v-if="datatable.header.filter" :content="datatable.header.filterCount" :value="datatable.header.filterCount" color="green" overlap>
        <v-btn @click="items({ action: 'filterData' })"
          color="blue-grey darken-1" class="ml-3 white--text">
          <v-icon dark>mdi-filter</v-icon>
        </v-btn>
      </v-badge>
    </v-toolbar>

    <v-data-table
      v-model="datatable.modelMain"
      :headers="datatable.header.titles"
      :show-select="datatable.showSelect"
      :single-select="datatable.singleSelect"
      :items="datatable.body.data"
      :options.sync="pagination"
      :server-items-length="datatable.body.totalData"
      :hide-default-footer="!datatable.footer.paging"
      :footer-props="footer"
      :loading="datatable.header.loading"
      loading-text="Cargando por favor espere"
      class="elevation-1"
      dense>

          <template v-slot:no-data>
              <v-alert :value="true" class="ma-1" borde="left" type="info" icon="warning" dense>
                  Lo siento, no hay registros para mostrar :(
              </v-alert>
          </template>

          <template #footer.page-text="props">
            {{props.pageStart}} - {{props.pageStop}} de {{props.itemsLength}}
          </template>

          <template v-slot:item.Status="{ item }">
            <v-chip v-if="item.last_movement" class="text-uppercase" :color="item.last_movement.estatus.color" outlined label small>
              {{ item.last_movement.estatus.estatus }}
            </v-chip>
          </template>

          <template v-slot:item.Actividad="{ item }">
            <v-chip class="text-uppercase" :color="(item.tipo_actividad.color) ? item.tipo_actividad.color : 'info'" outlined label small> {{ item.tipo_actividad.nombre }} </v-chip>
          </template>

          <template v-slot:item.Activo="{ item }">
            <v-chip class="text-uppercase" :color="(item.activo == 'si')? 'success':'error'" outlined label small> {{ item.activo }} </v-chip>
          </template>

          <template v-slot:item.Programado="{ item }">
            <v-chip class="text-uppercase" v-if="item.descripcion.programar && item.descripcion.programar === 'Programado'" color="orange" outlined label small> SI </v-chip>
            <v-chip class="text-uppercase" v-else color="green" outlined label small> NO </v-chip>
          </template>

          <template v-slot:item.Disponible="{ item }">
            <v-chip class="ma-1" color="info" outlined dense>
              {{ DisponibleInventario(item) }}
            </v-chip>
          </template>

          <template v-slot:item.SolicitanteSelected="{ item }">
            {{ selectedSolicitante(item) }}
          </template>

          <template v-slot:item.DepartamentoSelected="{ item }">
            <span>
              {{ selectedSolicitanteDepartamento(item) }}
            </span>
          </template>

          <!--
          <template v-slot:item.Actions="{ item }">
            <v-menu offset-y v-if="datatable.body.actions.length > 0">
              <template v-slot:activator="{ attrs, on }">
                <v-btn color="green darken-3" class="white--text ma-1" v-bind="attrs" v-on="on" small>
                  <v-icon>mdi-cog-box</v-icon>
                </v-btn>
              </template>
              <v-list dense>
                <span v-for="(action, index) in datatable.body.actions" :key="index">
                    <v-list-item v-if="action.hidden == ''" @click="items({action:action.action, item:item, body: action})" link>
                      <v-list-item-icon>
                        <v-icon :color="action.color">{{action.icon}}</v-icon>
                      </v-list-item-icon>
                      <v-list-item-title v-text="action.title" />
                    </v-list-item>
                </span>
              </v-list>
            </v-menu>
            <span v-if="item.last_movement && item.last_movement.actions.length > 0">
              <span v-if="item.last_movement.actions.length == 1 ">
                <v-btn v-for="(action, index) in item.last_movement.actions" :key="index" small :color="action.status_finish.color" dark class="mr-1"
                  @click="items({ action:action.accion, item:item, body:action})"> {{ action.status_finish.nombre }}
                    <v-icon right dark v-if="item.icon">{{ item.icon }}</v-icon>
                </v-btn>
              </span>

              <v-menu offset-y v-else>
                <template v-slot:activator="{ attrs, on }">
                  <v-btn color="teal darken-1" class="white--text ma-1" v-bind="attrs" v-on="on" small>
                    Acciones
                  </v-btn>
                </template>
                <v-list dense>
                  <v-list-item v-for="(action, index) in item.last_movement.actions" :key="index" link
                    @click="items({action:action.accion, item:item, body:action})">
                    <v-list-item-title v-if="action.status_finish.nombre" v-text="action.status_finish.nombre" />
                  </v-list-item>
                </v-list>
              </v-menu>
            </span>
          </template>
          -->
          <template v-slot:item.Actions="{ item }">
            <span>
              <v-menu offset-y>
                <template v-slot:activator="{ attrs, on }">
                  <v-btn color="teal darken-1" class="white--text ma-1" v-bind="attrs" v-on="on" small>
                    <v-icon>mdi-cog</v-icon>
                  </v-btn>
                </template>
                <v-list dense>
                  <!-- Acciones procesos -->
                  <v-list-item-group>
                  <span v-if="item.last_movement && item.last_movement.actions.length > 0">
                    <v-list-item v-for="(action, index) in item.last_movement.actions" :key="index" link
                      @click="items({action:action.accion, item:item, body: action})">
                      <v-list-item-icon>
                        <v-icon :color="action.status_finish.color">mdi-decagram</v-icon>
                      </v-list-item-icon>
                      <v-list-item-content>
                        <v-list-item-title>{{action.status_finish.nombre}}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </span>
                  </v-list-item-group>
                  <!-- Divisor -->
                  <v-list-item dense>
                    <v-divider/>
                    <v-subheader class="black--text font-weight-bold text-center">Generales</v-subheader>
                    <v-divider/>
                  </v-list-item>
                  <!-- Acciones Locales -->
                  <v-list-item-group>
                    <span v-for="(event, index) in datatable.body.actions" :key="index">
                      <v-list-item link v-if="event.hidden == ''"
                        @click="items({action:event.action, item:item, body:event})">
                        <v-list-item-icon>
                          <v-icon :color="event.color">{{event.icon}}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-title> {{event.title}} </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </span>
                  </v-list-item-group>
                </v-list>
              </v-menu>
            </span>
          </template>

          <template v-slot:item.ActionsLocal="{ item }">
                <v-menu offset-y>
                    <template v-slot:activator="{ attrs, on }">
                    <v-btn color="green darken-3" class="white--text ma-1" v-bind="attrs" v-on="on" small>
                      <v-icon>mdi-cog-box</v-icon>
                    </v-btn>
                    </template>
                    <v-list dense>
                    <v-list-item v-for="(action, index) in datatable.body.actions" :key="index" link
                        @click="items({action:action.action, item:item, body: action})">
                        <v-list-item-title v-text="action.title" />
                    </v-list-item>
                    </v-list>
                </v-menu>
          </template>
    </v-data-table>

  </div>
</template>

<script>
export default {
  name: 'TableMain',
  props: ['datatable', 'onSuccess', 'itemsAction', 'onPagination', 'onSearch', 'expresionSearch'],
  data: () => ({
    expand: false,
    buscar: '',
    footer: {
      showFirstLastPage: true,
      'items-per-page-text': 'Registros página'
    },
    pagination: {}
  }),
  methods: {
    action (item) {
      if (item.pagination) {
        item.pagination = this.pagination
      }
      this.onSuccess(item)
    },
    items (item) {
      this.itemsAction(item)
    },
    convertDate (date) {
      const dateString = new Date(date).toISOString().substr(0, 10)
      const timeString = new Date(date).toLocaleTimeString()
      return `${dateString} ${timeString}`
    },
    selectedSolicitante (item) {
      if (item.descripcion.solicitante_id) {
        return item.descripcion.solicitante.NombreCompleto
      } else {
        return item.descripcion.solicitante
      }
    },
    selectedSolicitanteDepartamento (item) {
      if (item.descripcion.solicitante_id) {
        return item.descripcion.solicitante.departamento.nombre
      } else {
        return 'Sin Departamento'
      }
    }
  },
  watch: {
    pagination (val) {
      if (this.buscar) {
        this.onSearch({
          itemsPerPage: val.itemsPerPage,
          page: val.page,
          search: this.buscar,
          sortDesc: this.pagination.sortDesc,
          sortBy: this.pagination.sortBy
        })
      } else {
        this.onPagination(val)
      }
    },
    buscar (val) {
      if (val == null || val === '') {
        this.onPagination(val)
        return
      }
      this.onSearch({
        itemsPerPage: this.pagination.itemsPerPage,
        page: this.pagination.page,
        search: val,
        sortDesc: this.pagination.sortDesc,
        sortBy: this.pagination.sortBy
      })
    },
    expresionSearch (val) {
      if (val) {
        this.buscar = val
      }
    }
  }
}
</script>

<style>
.inputSearch {
  padding-top: 25px !important;
}
</style>

<template>
  <div>
      <v-toolbar flat color="white" class="elevation-3">
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
        <v-spacer v-if="datatable.header.options.length > 0" />
        <v-text-field
          v-model="buscar"
          label="Buscar"
          class="inputSearch"
          clearable
          outlined
          dense>
        </v-text-field>
      </v-toolbar>
      <v-data-table
          v-model="datatable.modelMain"
          :show-select="datatable.showSelect"
          :single-select="datatable.singleSelect"
          :headers="datatable.header.titles"
          :items="datatable.body.data"
          :hide-default-footer="!datatable.footer.paging"
          :footer-props="footer"
          :search="buscar"
          :loading="datatable.header.loading"
          loading-text="Cargando por favor espere"
          class="elevation-3"
          dense>
          <template v-slot:no-data>
              <v-alert :value="true" class="ma-1" border="left" type="info" icon="warning" dense>
                  Lo siento, no hay registros para mostrar :(
              </v-alert>
          </template>

          <template #footer.page-text="props">
            {{props.pageStart}} - {{props.pageStop}} de {{props.itemsLength}}
          </template>

          <template v-slot:item.Status="{ item }">
            <v-chip v-if="item.last_movement" :color="item.last_movement.estatus.color" outlined label>
              {{ item.last_movement.estatus.estatus }}
            </v-chip>
          </template>
          <template v-slot:item.Actividad="{ item }">
            <v-chip color="info" outlined label> {{ item.actividad.nombre }} </v-chip>
          </template>

          <template v-slot:item.Activo="{ item }">
            <v-chip class="ma-1" :color="(item.activo == 'si')? 'success':'error'" small label outlined>
              <span class="font-weight-bold text-uppercase">{{ item.activo }}</span>
            </v-chip>
          </template>
          <template v-slot:item.Programado="{ item }">
            <v-chip v-if="item.descripcion.programar && item.descripcion.programar === 'Programado'"
              class="ma-1" color="orange" outlined label> SI </v-chip>
            <v-chip v-else class="ma-1" color="green" outlined label> NO </v-chip>
          </template>
          <template v-slot:item.Disponible="{ item }">
            <v-chip class="ma-1" :color="(DisponibleInventario(item) > 0 )? 'success':'error'"
              small label outlined>
              <span class="font-weight-bold text-uppercase">{{ DisponibleInventario(item) }}</span>
            </v-chip>
          </template>

          <template v-slot:item.Actions="{ item }">

            <v-menu offset-y>
              <template v-slot:activator="{ attrs, on }">
                <v-btn color="teal darken-2" class="white--text ma-1" v-bind="attrs" v-on="on" small>
                  <v-icon left>mdi-cog</v-icon> Acciones
                </v-btn>
              </template>
              <v-card>
                <v-list dense v-if="item.last_movement && item.last_movement.actions.length > 0">
                  <v-list-item v-for="(action, index) in item.last_movement.actions" :key="index" link
                    @click="items({action:action.accion, item:item, body:action})">
                    <v-list-item-title v-if="action.status_finish.nombre">
                      <v-icon :color="action.status_finish.color">mdi-decagram</v-icon>
                      {{ action.status_finish.nombre }}
                    </v-list-item-title>
                  </v-list-item>
                </v-list>

                <v-divider/>

                <v-list dense>
                  <span v-for="(action, index) in datatable.body.actions" :key="index">
                    <v-list-item @click="items({ body: action, action: action.action, item: item})" link>
                      <v-list-item-title>
                        <v-icon :color="action.color">{{action.icon}}</v-icon> {{action.title}}
                      </v-list-item-title>
                    </v-list-item>
                  </span>
                </v-list>

              </v-card>
            </v-menu>

          </template>

          <template v-slot:item.ActionsLocal="{ item }">
            <span v-for="(action, index) in datatable.body.actions" :key="index">
              <v-btn :color="action.color" class="mr-1" small dark
                @click="items( { body: action, action:action.action, item:item})"> {{ action.title }}
                <v-icon right dark v-if="action.icon">{{ action.icon }}</v-icon>
              </v-btn>
            </span>
          </template>

      </v-data-table>
  </div>
</template>

<script>
export default {
  name: 'TableMain',
  props: ['datatable', 'onSuccess', 'itemsAction'],
  data: () => ({
    expand: false,
    buscar: '',
    footer: {
      showFirstLastPage: true,
      'items-per-page-text': 'Registros por página'
    }
  }),
  methods: {
    action (item) {
      this.onSuccess(item)
    },
    items (item) {
      this.itemsAction(item)
    },
    DisponibleInventario (item) {
      if (item.last_movement) {
        if (item.last_movement.detalles.length > 0) {
          let disponible = 0
          item.last_movement.detalles.map((item) => {
            const cantidad = parseInt(item.descripcion.cantidad)
            disponible += cantidad
          })
          return disponible
        }
      }
      return 0
    }
  }
}
</script>

<style>
.inputSearch{
  padding-top: 25px !important;
}
</style>

<template>
  <div>
      <v-toolbar flat color="white" class="elevation-2" >
        <v-btn small v-for="(item, index) in datatable.header.options" :key="index"
          :color="item.color" dark class="mr-1" @click="action(item)">
            {{ item.title }}
            <v-icon right dark v-if="item.icon">{{ item.icon }}</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
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
          :headers="datatable.header.titles"
          :items="datatable.body.data"
          :hide-default-footer="!datatable.footer.paging"
          class="elevation-1"
          single-expand
          show-expand
          dense
          :search="buscar"
          :footer-props="footer">
          <template #footer.page-text="props">
            {{props.pageStart}} - {{props.pageStop}} de {{props.itemsLength}}
          </template>
          <!--
          <template v-slot:item="{ item }">
              <tr :style="`background:#${item.last_movement.estatus.color}`">
                <td>
                  {{ item.folio }}
                </td>
              </tr>
          </template>-->
          <template v-slot:item.Status="{ item }">
            <v-chip :color="item.last_movement.estatus.color" dark> {{ item.last_movement.estatus.estatus }} </v-chip>
          </template>
          <template v-slot:item.Programado="{ item }">
            <v-chip v-if="item.descripcion.programar && item.descripcion.programar === 'Programado'"
              class="ma-1" color="orange" outlined dense>SI</v-chip>
            <v-chip v-else class="ma-1" color="green" outlined dense>NO</v-chip>
          </template>

          <template v-slot:item.Actions="{ item }">
            <v-menu offset-y v-if="item.last_movement">
              <template v-slot:activator="{ attrs, on }">
                <v-btn color="teal darken-1" class="white--text ma-1" v-bind="attrs" v-on="on" small>
                  Acciones
                </v-btn>
              </template>
              <v-list dense>
                <v-list-item v-for="(action, index) in item.last_movement.actions" :key="index" link
                  @click="items( {action:action.accion, item:item, body:action})">
                  <v-list-item-title>{{ action.status_finish.estatus }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
            <v-menu offset-y v-else>
              <template v-slot:activator="{ attrs, on }">
                <v-btn color="teal darken-1" class="white--text ma-1" v-bind="attrs" v-on="on" small>
                  Acciones
                </v-btn>
              </template>
              <v-list dense>
                <v-list-item v-for="(action, index) in item.actions" :key="index" link
                  @click="items( {action:action.accion, item:item, body:action})">
                  <v-list-item-title>{{ action.status_finish.estatus }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>

          </template>
          <template v-slot:expanded-item="{ item }">
            <div v-if="item.contacto.length > 0">
              <tr
                v-for="contacto in item.contacto"
                :key="contacto.contacto"
                dense
                class="text-start d-block pl-2 py-2"
                align-cente
              >
              <div class="d-flex align-center" v-if="item.contacto.length > 0">
                <v-list-item-icon dense class="margin-icon">
                  <v-icon size="18px" v-if="contacto.tipo === 'TELEFONO'">mdi-phone-classic</v-icon>
                  <v-icon size="18px" v-if="contacto.tipo ==='CORREO'">mdi-email</v-icon>
                  <v-icon size="18px" v-if="contacto.tipo ==='MOVIL'">mdi-cellphone-android</v-icon>
                </v-list-item-icon>
                <strong>{{contacto.contacto}}</strong>
                </div>
              </tr>
            </div>
            <div v-else class="py-2 px-4">
              <tr>
                No cuenta con algún contacto
                </tr>
            </div>
          </template>
      </v-data-table>
  </div>
</template>

<script>
export default {
  name: 'TableMainProveedor',
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
    }
  }
}
</script>

<style>
.inputSearch{
  padding-top: 25px !important;
}
.margin-icon {
  margin: 0px 5px !important;
}
</style>

<template>
  <div>
    <v-toolbar color="white">
      <v-toolbar-items class="hidden-sm-and-down">
        <span
          v-for="(item, index) in datatable.header.options"
          :key="index"
          class="pt-4"
        >
          <span v-if="item.hidden == ''">
            <v-btn
              v-if="item.action"
              small
              :color="item.color"
              dark
              class="mr-1"
              @click="action(item)"
            >
              {{ item.title }}
              <v-icon
                v-if="item.icon"
                right
                dark
              >{{ item.icon }}</v-icon>
            </v-btn>
            <v-menu
              v-else
              offset-y
            >
              <template #activator="{ attrs, on }">
                <v-btn
                  color="teal darken-1"
                  class="white--text"
                  v-bind="attrs"
                  small
                  v-on="on"
                >
                  {{ item.title }} <v-icon
                    right
                    dark
                  >{{ item.icon }}</v-icon>
                </v-btn>
              </template>
              <v-list dense>
                <v-list-item
                  v-for="(option, index) in item.options"
                  :key="index"
                  link
                  dense
                  @click="action(option)"
                >
                  <v-list-item-icon>
                    <v-icon>{{ option.icon }}</v-icon>
                  </v-list-item-icon>
                  <v-list-item-subtitle>{{ option.title }}</v-list-item-subtitle>
                </v-list-item>
              </v-list>
            </v-menu>
          </span>
        </span>
      </v-toolbar-items>
      <span class="hidden-md-and-up">
        <v-menu :close-on-content-click="false">
          <template #activator="{ attrs, on }">
            <v-app-bar-nav-icon
              v-bind="attrs"
              small
              v-on="on"
            />
            <!--
                <v-btn color="teal darken-1" class="white--text"
                    v-bind="attrs" v-on="on" small> <v-icon dark>mdi-cog</v-icon>
                </v-btn>-->
          </template>
          <v-list dense>
            <span
              v-for="(item, index) in datatable.header.options"
              :key="index"
            >
              <span v-if="item.hidden == ''">
                <v-list-item
                  v-if="item.action"
                  link
                  dense
                  @click="action(item)"
                >
                  <v-list-item-icon>
                    <v-icon>{{ item.icon }}</v-icon>
                  </v-list-item-icon>
                  <v-list-item-subtitle>{{ item.title }}</v-list-item-subtitle>
                </v-list-item>
                <v-list-group
                  v-else
                  :value="true"
                  no-action
                  sub-group
                >
                  <template #activator>
                    <v-list-item-content>
                      <v-list-item-title>{{ item.title }} <v-icon
                        right
                        dark
                      >{{ item.icon }}</v-icon></v-list-item-title>
                    </v-list-item-content>
                  </template>
                  <v-list-item
                    v-for="(option, index) in item.options"
                    :key="index"
                    link
                    dense
                    @click="action(option)"
                  >
                    <v-list-item-icon>
                      <v-icon>{{ option.icon }}</v-icon>
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

      <v-text-field
        v-if="datatable.header.filter"
        v-model="buscar"
        label="Buscar"
        class="inputSearch"
        clearable
        outlined
        dense
      />
      <!-- <v-btn v-if="datatable.header.filter" @click="items({ action: 'filterData' })"
        color="blue-grey darken-1" class="ml-3 white--text" >
        <v-icon dark>mdi-filter</v-icon>
      </v-btn> -->
    </v-toolbar>

    <v-data-table
      v-model="datatable.modelMain"
      :item-key="datatable.itemKey"
      :show-select="datatable.showSelect"
      :single-select="datatable.singleSelect"
      :headers="datatable.header.titles"
      :items="datatable.body.data"
      :hide-default-footer="!datatable.footer.paging"
      :footer-props="footer"
      :search="buscar"
      :loading="datatable.header.loading"
      loading-text="Cargando por favor espere"
      :item-class="rowClassColor"
      class="elevation-1"
      dense>
      <template #no-data>
        <v-alert
          :value="true"
          color="info"
          class="ma-1"
          borde="left"
          type="info"
          icon="warning"
          dense
        >
          Lo siento, no hay registros para mostrar :(
        </v-alert>
      </template>
      <template #footer.page-text="props">
        {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
      </template>
      <!-- ================================================= -->
      <!-- ================================================= -->

      <template #item.Status="{ item }">
        <v-chip
          v-if="item.last_movement"
          class="font-weight-bold text-uppercase"
          :color="item.last_movement.estatus.color"
          outlined
          label
          small
        >
          {{ item.last_movement.estatus.estatus }}
        </v-chip>
      </template>
      <template #item.Activo="{ item }">
        <v-chip class="ma-1" :color="(item.activo == 'si')? 'success':'error'" outlined small label>
          <span class="font-weight-bold text-uppercase">{{ item.activo }}</span>
        </v-chip>
      </template>

      <template #item.Utilizar="{ item, header }" >
        <v-icon color="warning" class="mx-1" @click="items({ action: 'InsumoRemoved', item: item })">mdi-minus-circle
        </v-icon>
          <span class="text-h6" >{{ item[header.fieldMain] }}</span>
        <v-icon color="success" class="mx-1" @click="items({ action: 'InsumoAdded', item: item })">mdi-plus-circle
        </v-icon>
      </template>

      <template v-slot:item.AccessAll="{ item }">
        <span v-if="item.descripcion">
          <v-chip v-if="item.descripcion.special" class="ma-1" color="info" label outlined> {{ item.descripcion.special }} </v-chip>
          <v-chip v-else class="ma-1" color="info" label outlined> Normal </v-chip>
        </span>
        <span v-else>
          <v-chip class="ma-1" color="error" dark outlined> Sin Acceso </v-chip>
        </span>
      </template>

      <template v-slot:item.DinamicObjectData="{ item, header }">
        {{ ShowDataObject(item, header) }}
      </template>

      <template #item.DinamicImporte="{ item, header }">
        <span class="black--text font-weight-bold"> $ {{  intlRound(item[header.fieldMain]) }}</span>
      </template>

      <template v-slot:item.DateDinamic="{item, header}">
          {{ DateFormat(item[header.showItemColum]) }}
      </template>
      <template v-slot:item.ObjetDateDinamic="{item, header}">
          {{ ObjectDateFormat({item: item, ItemColum: header.showItemColum}) }}
      </template>
      <!-- ================================================= -->
      <!-- ================================================= -->
      <template #item.Actions="{ item }">
        <span>
          <v-menu offset-y>
            <template #activator="{ attrs, on }">
              <v-btn
                color="teal darken-1"
                class="white--text ma-1"
                v-bind="attrs"
                small
                v-on="on">
                <v-icon>mdi-cog</v-icon>
              </v-btn>
            </template>
            <v-list dense>
              <!-- Acciones procesos -->
              <v-list-item-group>
                <span v-if="item.last_movement && item.last_movement.actions.length > 0">
                  <v-list-item
                    v-for="(action, index) in item.last_movement.actions"
                    :key="index"
                    link
                    @click="items({action:action.accion, item:item, body:action})"
                  >
                    <v-list-item-icon>
                      <v-icon :color="action.status_finish.color">mdi-decagram</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title>{{ action.status_finish.nombre }}</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </span>
              </v-list-item-group>

              <!-- Divisor -->
              <v-list-item dense>
                <v-divider />
                <v-subheader class="black--text font-weight-bold text-center">Generales</v-subheader>
                <v-divider />
              </v-list-item>

              <!-- Acciones Locales -->
              <v-list-item-group>
                <span
                  v-for="(event, index) in datatable.body.actions"
                  :key="index"
                >
                  <v-list-item
                    v-if="event.hidden == ''"
                    link
                    @click="items({action:event.action, item:item, body:action})"
                  >
                    <v-list-item-icon>
                      <v-icon :color="event.color">{{ event.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title> {{ event.title }} </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </span>
              </v-list-item-group>
            </v-list>
          </v-menu>
        </span>
      </template>
      <template #item.ActionsGral="{ item }">
        <span>
          <v-menu offset-y>
            <template #activator="{ attrs, on }">
              <v-btn
                color="teal darken-1"
                class="white--text ma-1"
                v-bind="attrs"
                small
                v-on="on">
                <v-icon>mdi-cog</v-icon>
              </v-btn>
            </template>
            <v-list dense>
              <!-- Acciones Locales -->
              <v-list-item-group>
                <span v-for="(event, index) in datatable.body.actions" :key="index">
                  <v-list-item v-if="event.hidden == ''" link @click="items({action:event.action, item:item, body:event})">
                    <v-list-item-icon>
                      <v-icon :color="event.color">{{ event.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title class="font-weight-bold black--text subtitle-2"> {{ event.title }} </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </span>
              </v-list-item-group>
            </v-list>
          </v-menu>
        </span>
      </template>

      <template #item.Created="{ item }">
        {{ convertDate(item.created_at) }}
      </template>
      <template #item.Updated="{ item }">
        {{ convertDate(item.updated_at) }}
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { NumberUtils } from "@/mixins/NumberMixin"
import { DatesUtils } from "@/mixins/NumberMixin"

export default {
  name: 'TableMain',
  props: ['datatable', 'onSuccess', 'itemsAction'],
  mixins: [NumberUtils, DatesUtils],
  data: () => ({
    expand: false,
    buscar: '',
    udm_selected: null,
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
    events (header, item) {
      return this[header.action](header.field, item)
    },
    rowClassColor (item) {
      if (this.datatable.class_items) return this.itemsAction({ action: 'rowColor', item: item })
    },
    convertDate (date) {
      if (date) {
        const dateString = new Date(date).toISOString().substr(0, 10)
        const timeString = new Date(date).toLocaleTimeString()
        return `${dateString} ${timeString}`
      }
      return 'Sin Fecha'
    },
    AsistenciaHorasFaltas (val, item) {
      if (item.especial !== null && item.especial.horas) {
        return `${item[val]} Horas`
      }
      return `${item[val]} Dias`
    },
    ProcesadoAsistencias (val, item) {
      return (item.especial[val]) ? 'Aplicado' : 'No Aplicado'
    },
    setActiveRow (obje) {
      this.itemsAction(obje)
    },
    ShowDataObject (item, header) {
      const arbol = header.fieldMain.split('.')
      // console.log(item)
      let data = null
      arbol.map(i => {
        if (data) data = data[i]
        else data = item[i]
      })
      if (header.type === 'number' || header.type === 'textcenter') {
        if (!data) return 0
      } else if (header.type === 'json') {
        return JSON.stringify(data)
      }
      return data
    },

    DateFormat (columItem) {
      if (columItem) {
        return this.formatoFechaAbreviacion(columItem)
      }
      return ''
    },
    ObjectDateFormat ({ item, ItemColum }) {
      const objetsName = ItemColum.split('.')
      let lastobjet = item
      let exist = true
      objetsName.map(obj => {
        if (lastobjet[obj]) {
          lastobjet = lastobjet[obj]
        } else {
          exist = false
        }
      })
      if (exist) {
        return this.formatoFechaAbreviacion(lastobjet)
      }
      return ''
    }
  }
}
</script>

<style>
.inputSearch{
  padding-top: 25px !important;
}

.select-udm{
  border: 1px red solid;
  display: flex !important;
  align-items: center !important;
  width: 50%;
}
</style>

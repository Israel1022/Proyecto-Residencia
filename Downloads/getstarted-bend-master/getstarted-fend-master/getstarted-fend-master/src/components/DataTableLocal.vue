<template>
  <div>
    <v-toolbar flat color="white" class="elevation-2" >
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
        :footer-props="footer"
        :search="buscar"
        :loading="datatable.header.loading"
        :single-expand="datatable.singleExpand"
        :show-expand="datatable.showExpand"
        loading-text="Cargando por favor espere"
        class="elevation-1"
        dense>
        <template v-slot:no-data>
          <v-alert :value="true" class="ma-3" type="info" icon="warning" dense>
              Lo siento, no hay registros para mostrar :(
          </v-alert>
        </template>
            <template v-slot:item.Status="{ item }">
                <v-chip v-if="item.last_movemen" :color="item.last_movement.estatus.color" dark> {{ item.last_movement.estatus.estatus }} </v-chip>
            </template>
            <template v-slot:item.Activo="{ item }">
              <v-chip class="ma-1" :color="(item.activo == 'si')? 'success':'error'" small label outlined>
                <span class="font-weight-bold text-uppercase">{{ item.activo }}</span>
              </v-chip>
            </template>
            <template v-slot:item.Created="{ item }">
              {{ convertDate(item.created_at) }}
            </template>
            <template v-slot:item.Updated="{ item }">
              {{ convertDate(item.updated_at) }}
            </template>

            <template v-slot:item.AccessAll="{ item }">
              <v-chip v-if="item.descripcion" class="ma-1" color="info" dark outlined> {{ item.descripcion.special }} </v-chip>
              <v-chip v-else class="ma-1" color="info" dark outlined> Normal </v-chip>
            </template>

          <template v-slot:item.Actions="{ item }">
              <v-menu offset-y>
                  <template v-slot:activator="{ attrs, on }">
                      <v-btn color="teal darken-1" class="white--text ma-1"
                          v-bind="attrs" v-on="on" small>
                          Acciones
                      </v-btn>
                  </template>
                  <v-list dense>
                    <span v-for="(event, index) in datatable.body.actions" :key="index">
                      <v-list-item v-if="event.hidden == ''" link @click="items( { body:event, action:event.action, item:item})" dense>
                          <v-list-item-title>
                            <v-icon :color="event.color" small>{{event.icon}}</v-icon> {{event.title}}
                          </v-list-item-title>
                      </v-list-item>
                    </span>
                  </v-list>
              </v-menu>
          </template>

        <template v-slot:expanded-item="{ headers, item }">
          <td :colspan="headers.length">
            <ViewDetails v-if="datatable.body.idDetail" :object="item" :type="datatable.body.detail"/>
          </td>
        </template>

        <template #footer.page-text="props">
        {{props.pageStart}} - {{props.pageStop}} de {{props.itemsLength}}
        </template>
    </v-data-table>
  </div>
</template>

<script>
import ViewDetails from '@/components/ViewDetails'
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
  components: {
    ViewDetails
  },
  methods: {
    action (item) {
      this.onSuccess(item)
    },
    items (item) {
      this.itemsAction(item)
    },
    convertDate (date) {
      const dateString = new Date(date).toISOString().substr(0, 10)
      const timeString = new Date(date).toLocaleTimeString()

      return `${dateString} ${timeString}`
    }
  }
}
</script>

<style>
.inputSearch{
  padding-top: 25px !important;
}
</style>

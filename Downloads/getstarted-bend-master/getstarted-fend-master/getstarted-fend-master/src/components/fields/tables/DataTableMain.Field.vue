<template>
  <v-card variant="elevated">
    <v-toolbar class="bg-white" density="compact" :elevation="5">
      <v-toolbar-items class="hidden-sm-and-down">
        <span v-for="(item, index) in datatable.header.options" :key="index" class="pt-4">
          <ButtonMain v-if="!item.hidden && item.action" class="pt-1"
            :item="item" :OnSuccess="action" />
          <ButtonMenu v-else-if="item.options" :item="item" :OnSuccess="action" />
        </span>
      </v-toolbar-items>
      <span class="hidden-md-and-up">
        <ButtonMenuReports :item="datatable.header.options" :on-success="action" />
      </span>
      <v-spacer />
      <v-text-field class="px-4"
        :loading="loading"
        v-model="buscar"
        prepend-inner-icon="mdi-magnify"
        density="compact"
        label="Buscar"
        variant="solo-filled"
        hide-details
        single-line
        clearable
      ></v-text-field>
    </v-toolbar>

    <v-data-table density="compact"
      :loading="datatable.header.loading"
      :headers="datatable.header.titles"
      :items="datatable.body.data"
      itemsPerPageText="Registros por página"
      :pageText="'{0}-{1} de {2}'"
      :search="buscar">
      <!-- ===*===*===*=== Funciones del Componente General ===*===*===*=== -->
      <template v-slot:loading>
        <v-skeleton-loader type="table-row@10"></v-skeleton-loader>
      </template>
      <template v-slot:no-data>
        <v-alert :value="true" class="ma-1" color="error" density="compact" icon="warning" theme="dark">
          Lo siento, no hay registros para mostrar :(
        </v-alert>
      </template>
      <!-- ===*===*===*=== Funciones del Componente General ===*===*===*=== -->

      <template v-slot:item.actions="{ item }">
        <v-menu location="start">
            <template v-slot:activator="{ props }">
              <v-btn icon="mdi-reorder-horizontal" v-bind="props" color="blue-grey-darken-1">
              </v-btn>
            </template>
            <v-list nav density="compact">
              <span v-for="(action, i) in datatable.body.actions" :key="i">
                <v-list-item class="text-caption" variant="plain">
                  <template v-slot:prepend>
                    <v-icon :icon="action.icon" :color="action.color"></v-icon>
                  </template>
                  <v-list-item-title>
                    <span class="font-weight-bold text-uppercase">{{action.title}}</span>
                  </v-list-item-title>
                </v-list-item>
              </span>
            </v-list>
          </v-menu>
      </template>
    </v-data-table>
  </v-card>
</template>
<script>
import ButtonMain from '@/components/fields/buttons/ButtonMain.Field.vue'
import ButtonMenu from '@/components/fields/buttons/ButtonMenu.Field.vue'
import ButtonMenuReports from '@/components/fields/buttons/ButtonMenuReports.Field.vue'
export default {
  name: 'DataTableMain',
  props: ['datatable', 'onSuccess'],
  data: () => ({
    loading: false,
    buscar: ''
  }),
  components: { ButtonMenu, ButtonMain, ButtonMenuReports },
  mounted() {
  },
  methods: {
    action (item) { 
      this.onSuccess(item)
    },
  },
  computed: {
  },
}
</script>
<style scoped></style>
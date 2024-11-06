<template>
  <v-container>
    <v-row justify="center" dense>
      <v-col cols="12" md="9">
        <DataTableMain :datatable="table" :on-success="actionTable"/>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapGetters, mapMutations } from 'vuex'
import DataTableMain from '@/components/fields/tables/DataTableMain.Field.vue'
export default {
  data: () => ({
    table: {
      header: {
        options: [
          { title: 'Refrescar', icon: 'mdi-cached', color: 'primary', action: 'refreshData', code: 'libre' },
          { title: 'Nuevo', icon: 'mdi-plus', color: 'success', action: 'newData', code: 'libre' }
        ],
        titles:[
        { title: 'Id', value: 'id', class: 'black--text' },
        { title: 'Nombre', value: 'name', class: 'black--text' },
        { title: 'Activo', value: 'activo', class: 'black--text' },
        { title: 'Fecha Creación', value: 'created_at', class: 'black--text' },
        { title: 'Fecha Actualización', value: 'updated_at', class: 'black--text' },
        { title: '', value: 'actions', align: 'end' }
        ],
        loading: false,
      },
      body: {
        data: [ {} ],
        actions: [
          { title: 'Editar', icon: 'mdi-pencil', color: 'yellow-darken-3', action: 'EditData', code: 'sys.catalogos.activities.update' },
          { title: 'Eliminar', icon: 'mdi-delete', color: 'red-darken-3', action: 'DeleteData', code: 'sys.catalogos.activities.delete' }
        ]
      }
    }
  }),
  components: { DataTableMain },
  methods: {
    ...mapMutations(['SHOW_MAIN_PROGRESS_DIALOG']),
    actionTable(obj) {
      this[obj.action](obj);
    },
    refreshData (obj) {
      this.table.header.loading = true
    }
  },
  computed: {},
}
</script>
<style scoped>
</style>
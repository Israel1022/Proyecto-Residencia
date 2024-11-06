<template>
    <v-container fluid>
        <DataTable :datatable="table" :on-success="successAction" :itemsAction="tableAction"/>
    </v-container>
</template>

<script>
import TableMain from '@/components/dataTableMain'
export default {
  data: () => ({
    table: {
      header: {
        options: [
          { title: 'refresh', icon: 'mdi-cached', color: 'primary' },
          { title: 'create', icon: 'mdi-plus', color: 'success' },
          { title: 'Edit', icon: 'mdi-border-color', color: 'warning' },
          { title: 'Delete', icon: 'mdi-delete', color: 'error' }
        ],
        titles: [
          { text: 'Dessert (100g serving)', value: 'name', accions: [] },
          { text: 'Calories', value: 'calories' },
          { text: 'Fat (g)', value: 'fat' },
          { text: 'Carbs (g)', value: 'carbs' },
          { text: 'Protein (g)', value: 'protein' },
          { text: 'Iron (%)', value: 'iron' },
          { text: '', value: 'Actions' }
        ],
        loading: false
      },
      body: {
        data: [
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%',
            actions: [
              { accion: 'action', status_finish: { estatus: 'item 1' } },
              { accion: 'action', status_finish: { estatus: 'item 2' } },
              { accion: 'action', status_finish: { estatus: 'item 3' } },
              { accion: 'action', status_finish: { estatus: 'item 4' } }
            ]
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
            iron: '1%',
            actions: [
              { accion: 'action', status_finish: { estatus: 'item 1' } },
              { accion: 'action', status_finish: { estatus: 'item 2' } },
              { accion: 'action', status_finish: { estatus: 'item 3' } }
            ]
          },
          {
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
            iron: '7%',
            actions: [
              { accion: 'action', status_finish: { estatus: 'item 1' } },
              { accion: 'action', status_finish: { estatus: 'item 2' } }
            ]

          }]
      },
      footer: {
        paging: true
      }
    }
  }),
  mounted () {
  },
  methods: {
    // ...mapActions([]),
    successAction (item) {
    },
    tableAction (item) {
    }
  },
  computed: {
    // ...mapGetters(['get_options'])
  },
  components: {
    DataTable: TableMain
  }
}
</script>

<style>

</style>

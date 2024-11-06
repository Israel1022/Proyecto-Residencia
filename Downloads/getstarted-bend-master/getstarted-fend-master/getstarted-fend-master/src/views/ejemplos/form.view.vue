<template>
<v-container>
  <v-row dense>
    <FormsMain
      titleButton="Crear"
      :formCreate="form"
      :on-success="saveData"
      :on-cancel="cancel" />
  </v-row>
  <v-row dense>
    <v-col cols="12">
      {{ this.resp }}
    </v-col>
  </v-row>
</v-container>

</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import FormsMain from '@/components/forms/formsMainGral'
export default {
  data: () => ({
    mesaAyuda: {
      selected: [],
      action: '',
      status: 0
    },
    form: {
      fields: [
        {
          field: 'radioButton',
          name: 'programar',
          nameid: 'programar',
          items: ['Programado', 'No Programado'],
          isHidden: ['fInicial', 'fFinal'],
          action: 'ShowAndHeddenInput',
          cols: 6
        },
        {
          field: 'textDatePicker',
          name: 'Fecha Inicial',
          nameid: 'fInicial',
          hidden: true,
          cols: 3
        },
        {
          field: 'textDatePicker',
          name: 'Fecha Final',
          nameid: 'fFinal',
          hidden: true,
          cols: 3
        },
        {
          field: 'searchDataServer',
          name: 'Solicitante',
          nameid: 'empleadoId',
          url: '/empleados',
          cols: 6
        },
        {
          type: 'text',
          name: 'Equipo',
          nameid: 'equipo',
          field: 'text',
          cols: 3
        },
        {
          type: 'text',
          name: 'Invetario',
          nameid: 'inventario',
          field: 'text',
          cols: 3
        },
        {
          field: 'selectDataServer',
          name: 'Departamento',
          nameid: 'departamento',
          url: '/departamentos',
          cols: 6
        },
        {
          field: 'selectDataServer',
          name: 'Tipo Atencion',
          nameid: 'tipoAtencion',
          url: '/tipos-atencion',
          cols: 6
        },
        {
          type: 'text',
          name: 'Tel./Ext.',
          nameid: 'telExt',
          field: 'text',
          cols: 6
        },
        {
          type: 'text',
          name: 'Falla Reportada',
          nameid: 'fallaReportada',
          field: 'text',
          cols: 6
        },
        {
          field: 'textArea',
          dimens: 'input-7-1',
          name: 'Trabajo realizado',
          nameid: 'trabajo',
          cols: 6
        },
        {
          field: 'textArea',
          dimens: 'input-7-1',
          name: 'Observaciones',
          nameid: 'observaciones',
          cols: 6
        }
      ]
    },
    resp: {}
  }),
  mounted () {
    this.getFormDefault()
  },
  methods: {
    ...mapActions(['getFormDefault']),
    saveData (obj) {
      this.resp = obj
    },
    cancel () {

    }
  },
  computed: {
    ...mapGetters(['get_options'])
  },
  components: {
    FormsMain
  }
}
</script>

<style>

</style>

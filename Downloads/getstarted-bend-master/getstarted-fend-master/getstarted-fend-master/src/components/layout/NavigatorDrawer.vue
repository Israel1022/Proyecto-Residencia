<template>
  <LoadingDialog v-if="get_MainProgressDialog.dialog"/>
  <v-navigation-drawer :rail="rail" v-model="drawer">
      <v-img v-if="rail" class="text-center mx-1 mt-1" src="@/assets/logo.png"/>
      <v-img v-else class="text-center mx-1 mt-1" src="@/assets/logo.png"/>
    <v-divider class="mx-1 my-3"></v-divider>
    <MenuRail v-if="rail" :menu="MenuMain" :OnSuccess="ItemAction"/>
    <MenuList v-else :menu="MenuMain" :OnSuccess="ItemAction"/>
  </v-navigation-drawer>
  <Toolbar @toggleNavigationBar="rail = !rail" :isactive="showMenu"/>
</template>
<script>
import { mapGetters } from 'vuex'
import router from '@/router'
import Toolbar from "@/components/layout/Toolbar.vue"
import MenuRail from "@/components/layout/menu/MenuRail.vue"
import MenuList from "@/components/layout/menu/MenuList.vue"
import Menu from "@/store/menu"
export default {
  data: () => ({ 
    drawer: true,
    rail: true,
    MenuMain:[],
    MenuMain1: [
      {
        id: 'dashboard.show',
        action: 'DashboardPage',
        icon: 'mdi-view-dashboard',
        title: 'Dashboard'
      },
      {
        id: 'agenda.show',
        action: 'AgendaPage',
        icon: 'mdi-calendar-multiple',
        title: 'Agenda Pacientes'
      },
      {
        id: 'calendar.show',
        action: 'CalendarioPage',
        icon: 'mdi-calendar-plus',
        title: 'Calendario'
      },
      {
        id: 'calendario.show',
        action: 'AgendaGralPage',
        icon: 'mdi-calendar-month',
        title: 'Calendario Eventos'
      },

      // Sub-Menu Catalogos
      {
        id: 'catalogos',
        icon: 'mdi-file-document-multiple',
        title: 'catálogos',
        group: true,
        items: [
          {
            id: 'catalogos.actividades.show',
            action: 'ActividadesPage',
            icon: 'mdi-view-list',
            title: 'Actividades'
          },
          {
            id: 'catalogos.actividades-extra.show',
            action: 'ActividadesExtraPage',
            icon: 'mdi-view-list',
            title: 'Actividades Extra'
          },
          {
            id: 'catalogos.areas.show',
            action: 'AreasPage',
            icon: 'mdi-view-list',
            title: 'Áreas'
          },
          {
            id: 'catalogos.areas-terapeuticas.show',
            action: 'AreasTerapeuticaPage',
            icon: 'mdi-view-list',
            title: 'Áreas Terapéuticas',
          },
          {
            id: 'catalogos.comites.show',
            action: 'ComitesPage',
            icon: 'mdi-view-list',
            title: 'Comites'
          },
          {
            id: 'catalogos.crf.show',
            action: 'CrfPage',
            icon: 'mdi-view-list',
            title: 'CRF'
          },
          {
            id: 'catalogos.cros.show',
            action: 'CroPage',
            icon: 'mdi-view-list',
            title: 'CRO'
          },
          {
            id: 'catalogos.diagnosticos.show',
            action: 'DiagnosticosPage',
            icon: 'mdi-view-list',
            title: 'Diagnosticos'
          },
          {
            id: 'catalogos.Estrategias.show',
            action: 'EstrategiasPage',
            icon: 'mdi-view-list',
            title: 'Estrategias'
          },
          {
            id: 'catalogos.familias.show',
            action: 'FamiliasPage',
            icon: 'mdi-view-list',
            title: 'Familias'
          },
          {
            id: 'catalogos.fuentes-hallazgos.show',
            action: 'FuentesHallazgosPage',
            icon: 'mdi-view-list',
            title: 'Fuentes de Hallazgos'
          },
          {
            id: 'catalogos.iwrs.show',
            action: 'IWRSPage',
            icon: 'mdi-view-list',
            title: 'IWRS'
          },
          {
            id: 'catalogos.Laboratorio-Central.show',
            action: 'LaboratorioCentralPage',
            icon: 'mdi-view-list',
            title: 'Laboratorio Central'
          },
          {
            id: 'catalogos.laboratorio.show',
            action: 'LaboratorioPage',
            icon: 'mdi-view-list',
            title: 'Laboratorio'
          },
          {
            id: 'catalogos.origenes.show',
            action: 'OrigenesPage',
            icon: 'mdi-view-list',
            title: 'Origenes'
          },
          {
            id: 'catalogos.patrocinadores.show',
            action: 'PatrocinadoresPage',
            icon: 'mdi-view-list',
            title: 'Patrocinadores'
          },
          {
            id: 'catalogos.productos.show',
            action: 'ProductosPage',
            icon: 'mdi-view-list',
            title: 'Productos'
          },
          {
            id: 'catalogos.proveedores.show',
            action: 'ProveedoresPage',
            icon: 'mdi-view-list',
            title: 'Proveedores'
          },
          {
            id: 'catalogos.puestos.show',
            action: 'PuestosPage',
            icon: 'mdi-view-list',
            title: 'Puestos'
          },
          {
            id: 'catalogos.tipos-impuestos.show',
            action: 'TiposImpuestosPage',
            icon: 'mdi-view-list',
            title: 'Tipos de Impuestos'
          },
          {
            id: 'catalogos.tipos-costos.show',
            action: 'TiposCostosPage',
            icon: 'mdi-view-list',
            title: 'Tipos de Costos'
          },
          {
            id: 'catalogos.tipo-documento.show',
            action: 'TipoDocumentoPage',
            icon: 'mdi-view-list',
            title: 'Tipo de Documento'
          },
          {
            id: 'catalogos.mensajeria-envios.show',
            action: 'MensajeriaEnviosPage',
            icon: 'mdi-view-list',
            title: 'Tipo Mensajeria de Envíos'
          },
          {
            id: 'catalogos.tipo-visita.show',
            action: 'TipoVisitaPage',
            icon: 'mdi-view-list',
            title: 'Tipo Visita'
          },
          {
            id: 'catalogos.tratamientos.show',
            action: 'TratamientosPage',
            icon: 'mdi-view-list',
            title: 'Tratamientos'
          },
          {
            id: 'catalogos.unidad-medidas.show',
            action: 'UnidadMedidasPage',
            icon: 'mdi-view-list',
            title: 'Unidad de Medidas'
          },
          {
            id: 'catalogos.via-administracion.show',
            action: 'ViaAdministracionPage',
            icon: 'mdi-view-list',
            title: 'Via Administración'
          }
        ],
      },
      {
        id: 'configuraciones',
        icon: 'mdi-cogs',
        title: 'Configuraciones',
        group: true,
        items: [
          {
            id: 'configuraciones.accesos',
            icon: 'mdi-file-document-multiple',
            title: 'Accesos',
            group: true,
            items: [
              {
                id: 'configuraciones.usuarios',
                action: 'UsuariosPage',
                icon: 'mdi-account-group',
                title: 'Usuarios'
              },
              {
                id: 'configuraciones.roles',
                action: 'RolesPage',
                icon: 'mdi-timeline-check',
                title: 'Roles'
              },
              {
                id: 'configuraciones.permisos',
                action: 'PermisosPage',
                icon: 'mdi-clipboard-list',
                title: 'Permisos'
              }
            ],
          }
        ]
      }
    ]
 }),
  components: { Toolbar, MenuRail, MenuList },
  mounted() {
    this.MenuMain = Menu
  },
  methods: {
    showMenu (active) {
      this.rail = active
    },
    ItemAction (item) {
      router.push({ name: item })
    }
  },
  computed: {
    ...mapGetters(['get_MainProgressDialog'])
  },
}
</script>
<style scoped>
</style>
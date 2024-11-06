import Vue from 'vue'
import VueRouter from 'vue-router'
// Configuracion
import EmpleadosView from './views/configuraciones/Empleados.view.vue'
import UsuariosView from './views/configuraciones/accesos/usuarios.view.vue'
import RolesView from './views/configuraciones/accesos/roles.view.vue'
import PermisosView from './views/configuraciones/accesos/permisos.view.vue'
import DepartamentoView from './views/catalogo/Departamentos.view.vue'
import FormCaracteristicaView from './views/configuraciones/FormCaracteristicas.view.vue'
// Catalogos
import TecnologiasView from './views/catalogo/Tecnologias.view.vue'
import ComunicacionsocialView from './views/catalogo/Comunicacionsocial.view.vue'
// Inventarios
// Configuraciones
import PanelEmpresaView from './views/configuraciones/procesos/PanelEmpresa.view.vue'

Vue.use(VueRouter)

export default new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'inicio',
      component: () => import(/* webpackChunkname: "login" */ './views/login.view.vue'),
      meta: {
        breadcrumb: [],
        name: ''
      }
    },
    {
      path: '/home',
      name: 'home',
      // beforeEnter
      meta: {
        allowAnonymous: true,
        breadcrumb: [{ name: 'Inicio' }],
        name: 'Sistema De Administracion De Archivos'
      },
      component: () => import(/* webpackChunkname: "Home" */ './views/home.view.vue')
    },
    // ========== Configuraciones ========== \\
    {
      path: '/Configuraciones/Empleados',
      name: 'ConfigEmpleadosView',
      meta: {
        allowAnonymous: true,
        name: 'Empleados',
        breadcrumb: [{ name: 'Configuraciones' }, { name: 'Empleados' }]
      },
      component: EmpleadosView
    },
    {
      path: '/Configuraciones/accesos/Usuarios',
      name: 'ConfigAuthUsersView',
      meta: {
        allowAnonymous: true,
        name: 'Usuarios',
        breadcrumb: [{ name: 'Configuraciones' }, { name: 'Acceso' }, { name: 'Usuarios' }]
      },
      component: UsuariosView
    },
    {
      path: '/Configuraciones/Acceso/Roles',
      name: 'ConfigAuthRolsView',
      meta: {
        allowAnonymous: true,
        name: 'Roles',
        breadcrumb: [{ name: 'Configuraciones' }, { name: 'Acceso' }, { name: 'Roles' }]
      },
      component: RolesView
    },
    {
      path: '/Configuraciones/Acceso/Permisos',
      name: 'ConfigAuthPermissView',
      meta: {
        allowAnonymous: true,
        name: 'Permisos',
        breadcrumb: [{ name: 'Configuraciones' }, { name: 'Acceso' }, { name: 'Permisos' }]
      },
      component: PermisosView
    },
    {
      path: '/Configuraciones/FormFamiliaCaracterristicas',
      name: 'FormCaracteristicaView',
      meta: {
        allowAnonymous: true,
        name: 'Formulario de Familia-Caracteristicas',
        breadcrumb: [{ name: 'Configuraciones' }, { name: 'Formulario de Familia y Caracteristicas' }]
      },
      component: FormCaracteristicaView
    },
    // ========== Catalogos ========== \\
    {
      path: '/Catalogos/Tecnologias',
      name: 'TecnologiasView',
      meta: {
        allowAnonymous: true,
        name: 'Tecnologias y servicios de la informacion',
        breadcrumb: [{ name: 'Catálogos' }, { name: 'Tecnologias' }]
      },
      component: TecnologiasView
    },
    {
      path: '/Catalogos/Departamentos',
      name: 'DepartamentoView',
      meta: {
        allowAnonymous: true,
        name: 'Departamentos',
        breadcrumb: [
          { name: 'Empresa' },
          { name: 'Departamentos' }
        ]
      },
      component: DepartamentoView
    },
    {
      path: '/Catalogo/Comunicacionsocial',
      name: 'ComunicacionsocialView',
      meta: {
        allowAnonymous: true,
        name: 'Comunicacion social y relaciones publicas',
        breadcrumb: [{ name: 'Catalogo' }, { name: 'Comunicacion Social' }]
      },
      component: ComunicacionsocialView
    },
    // ============================================================== \\
    // ============================================================== \\
    // ============================================================== \\
    // Mesa de ayuda \\
    {
      path: '/MesaAyuda/seguimiento',
      name: 'MeAyuSolicitudes',
      meta: {
        allowAnonymous: true,
        breadcrumb: [
          { name: 'Sistema de archivos' },
          { name: 'Seguimiento' }
        ],
        name: 'Seguimiento'
      },
      component: () => import('./views/mesaAyuda/seguimiento.view.vue')
    },
    // Catalogos \\
    {
      path: '/Catalogo/Roles/Permisos/:id',
      name: 'ViewPermissionsRoleAssign',
      meta: {
        allowAnonymous: true,
        name: 'Asignar Permisos a Rol',
        breadcrumb: [
          { name: 'Catalogo' },
          { name: 'Roles' },
          { name: 'Asignacion de Permisos' }
        ]
      },
      component: () => import('./views/company/rolePermissionsAssign.view.vue')
    },
    // Inventarios \\

    // {
    //   path: '/Inventarios/Proveedores',
    //   name: 'InvProveedores',
    //   meta: {
    //     allowAnonymous: true,
    //     breadcrumb: [
    //       { name: 'Inventarios' },
    //       { name: 'Proveedores' }
    //     ]
    //   },
    //   component: () => import('./views/inventarios/proveedores.view.vue')
    // },
    // Configuraciones \\
    {
      path: '/Configuraciones/TiposProcesos',
      name: 'ViewProcesos',
      meta: {
        allowAnonymous: true,
        name: 'Tipos de Procesos',
        breadcrumb: [
          { name: 'Configuraciones' },
          { name: 'Tipos de Procesos' }
        ]
      },
      component: () => import('./views/company/configuraciones/procesos.view.vue')
    },
    {
      path: '/Configuraciones/RutinasProcesos/PanelEmpresa',
      name: 'ConfigRutProcPanelEmpresa',
      meta: {
        allowAnonymous: true,
        name: 'Panel de Datos de Empresa',
        breadcrumb: [{ name: 'Configuraciones' }, { name: 'Rutinas y Procesos' }, { name: 'Panel de Empresa' }]
      },
      component: PanelEmpresaView
    }
    // Fin Configuraciones Fin \\
  ]
})

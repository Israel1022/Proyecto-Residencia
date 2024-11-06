import InicioPage from '@/pages/index.vue'

import LoginPage from '@/pages/login.view.vue'
import EjemploPage from '@/pages/catalogos/EjemploCatalogo.view.vue'

import DashboardPage from '@/pages/dashboard.view.vue'

// CATALOGOS VIEW \\
import ComunicacionsocialPage from '@/pages/catalogos/Comunicacionsocial.view.vue'

// CONFIGURACIONES VIEW \\
import ComponentesPage from '@/pages/configuraciones/Componentes.view.vue'

export default [
    { path: "/", name: 'Home', component: () => LoginPage },
    { path: "/Catalogo/Ejemplo", name: 'CatalogoEjemploPage', component: () => EjemploPage, meta: { requiresAuth: true } },
    { path: "/Dashboard", name: 'DashboardPage', component: () => DashboardPage, meta: { requiresAuth: true } },

    // { path: "/Calendario", name: 'CalendarioPage', component: () => CalendarioPage, meta: { requiresAuth: true } },
    // Catalogos
    { path: "/Catalogo/ComunicacionSocial", name: 'ComunicacionSocialPage', component: ComunicacionsocialPage, meta: { requiresAuth: true } },

        // Configuraciones
    { path: "/Configuraciones/Componentes", name: 'ConfiguracionComponentesPage', component: ComponentesPage, meta: { requiresAuth: true } }
]
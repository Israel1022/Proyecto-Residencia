export default [
    {
        id: 'dashboard.show',
        action: 'DashboardPage',
        icon: 'mdi-view-dashboard',
        title: 'Dashboard'
    },
    {
        id: 'catalogos',
        icon: 'mdi-file-document-multiple',
        title: 'catálogos',
        group: true,
        items: [
            {
                id: 'catalogos.comunicacion-social.show',
                action: 'ComunicacionSocialPage',
                icon: 'mdi-view-list',
                title: 'Comunicación Social'
            }
        ]
    },
    {
        id: 'configuracion',
        icon: 'mdi-cogs',
        title: 'Configuraciones',
        group: true,
        items: [
            {
                id: 'configuracion.componentes.show',
                action: 'ConfiguracionComponentesPage',
                icon: 'mdi-view-grid-outline',
                title: 'Componentes'
            }
        ]
    }
]
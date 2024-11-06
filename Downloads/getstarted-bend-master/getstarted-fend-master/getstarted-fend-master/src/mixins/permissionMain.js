export const Permission = {
  data: () => ({}),
  methods: {
    ShowPermisos ({ permisos, tableMain }) {
      if (permisos.descripcion.special === 'all-access') {
        tableMain.header.options.map(action => {
          action.hidden = ''
          if (action.options) {
            action.options.map(option => {
              option.hidden = ''
            })
          }
        })
        tableMain.body.actions.map(action => {
          action.hidden = ''
        })
        return
      }
      tableMain.header.options.map(action => {
        permisos.permisos.map(item => {
          if (action.code !== 'libre') {
            if (action.code === item.slug) {
              action.hidden = ''

              if (action.options) {
                action.options.map(subAction => {
                  permisos.permisos.map(permiss => {
                    if (subAction.code !== 'libre') {
                      if (subAction.code === permiss.slug) subAction.hidden = ''
                    }
                  })
                })
              }
            }
          }
        })
      })
      tableMain.body.actions.map(action => {
        permisos.permisos.map(item => {
          if (action.code !== 'libre') {
            if (action.code === item.slug) {
              action.hidden = ''
            }
          }
        })
      })
    },

    ReadOnlyPermiso (permisos, permiso) {
      if (permisos.descripcion.special === 'all-access') return true

      const acceso = permisos.permisos.find(item => item.slug === permiso)
      return (acceso)
    }
  }
}

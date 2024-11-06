export default ({
  state: {
    MainFormDialog: {
      identity: '',
      with: 600,
      dialog: false,
      title: '',
      form: 'form',
      body: {
        showCancel: true,
        showComfirm: true,
        nameCancel: 'Cancelar',
        nameComfirm: 'Guardar'
      },
    },
    MainProgressDialog: {
      with: 600,
      dialog: false,
      title: '',
      icon: '',
      progress: 'circular'
    },
    MainMessageDialog: {
      identity: '',
      with: 600,
      dialog: false,
      title: '',
      subtitle: '',
      icon: '',
      body: {
        showCancel: true,
        showComfirm: true,
        nameCancel: 'Cancelar',
        nameComfirm: 'Guardar'
      },
      data: {}
    },
    ConfirmData: {}
  },
  getters: {
    get_MainFormDialog (state) { return state.MainFormDialog },
    get_MainProgressDialog (state) { return state.MainProgressDialog },
    get_MainMessageDialog (state) { return state.MainMessageDialog },
    get_ConfirmData (state) { return state.ConfirmData }
  },
  mutations: {
    SHOW_MAIN_FORM_DIALOG (state, payload) {
      let bodydata = payload.body
      state.MainFormDialog = {
        identity: payload.identity,
        with: payload.with ? payload.with : '600',
        dialog: true,
        title: payload.title,
        form: payload.form_type ? payload.form_type : 'form',
        body: {
          showCancel: true,
          showComfirm: true,
          nameCancel: 'Cancelar',
          nameComfirm: 'Guardar'
        },
      }
    },
    HIDDEN_MAIN_FORM_DIALOG (state, payload) {
      state.MainFormDialog.dialog = false
      state.MainFormDialog.title = ''
      state.MainFormDialog.form = 'form'

    },

    SHOW_MAIN_PROGRESS_DIALOG (state, payload) {
      state.MainProgressDialog = {
        with: payload.with ? payload.with : '600',
        dialog: true,
        title: payload.title,
        icon: payload.icon ? payload.icon : '$vuetify-outline',
        progress: payload.progress ? payload.progress : 'circular'
      }
    },
    HIDDEN_MAIN_PROGRESS_DIALOG (state, payload) {
      state.MainProgressDialog = {
        with: '600',
        dialog: false,
        title: '',
        icon: '',
        progress: 'circular'
      }
    },

    SHOW_MAIN_MESSAGE_DIALOG (state, payload) {
      state.MainMessageDialog = {
        identity: payload.identity,
        with: payload.with ? payload.with : '600',
        dialog: true,
        title: payload.title,
        subtitle: payload.subtitle,
        icon: payload.subtitle,
        body: payload.body,
        data: payload.data
      }
    },
    HIDDEN_MAIN_MESSAGE_DIALOG (state, payload) {
      state.MainMessageDialog.dialog = false
      state.MainMessageDialog.title = ''
      state.MainMessageDialog.subtitle = ''
    },

    CONFIRM_MAIN_MESSAGE_DIALOG (state, payload) {
      state.ConfirmData = payload
    }
  },
})
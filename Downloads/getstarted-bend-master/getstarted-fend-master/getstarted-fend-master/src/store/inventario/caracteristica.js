import axios from 'axios'
import Vue from 'vue'

export default {
  state: {
    dialog_form: {
      dialog: false,
      title: ''
    },
    invCaracteristicas: []
  },
  getters: {
    get_invCaracteristicas (state) {
      return state.invCaracteristicas
    },
    get_dialog_caracteristica (state) {
      return state.dialog_form
    }
  },
  mutations: {
    OPEN_DIALOG_CARACTER_FORM (state, data) {
      state.dialog_form.dialog = true
      state.dialog_form.title = data.title
    },
    CLOSE_DIALOG_CARACTER_FORM (state) {
      state.dialog_form.dialog = false
      state.dialog_form.title = ''
    },
    UPDATE_INV_CARACTERISTICAS (state, newCaracteristica) {
      state.invCaracteristicas.push(newCaracteristica)
    },
    GET_INV_CARACATERISTICAS (state, carateristica) {
      state.invCaracteristicas = []
      state.invCaracteristicas.push(...carateristica)
    }
  },
  actions: {
    getCaracteristicas ({ commit }) {
      axios.get('caracteristicas')
        .then(response => {
          commit('GET_INV_CARACATERISTICAS', response.data)
        })
        .catch(error => {
          if (error !== null) {
            Vue.swal({
              type: 'error',
              title: 'Ooops!',
              text: 'Problemas con el servidor, catarcate al de sistema !!'
            })
          }
        })
    },
    postSaveCaracteristica ({ commit }, form) {
      axios.post('caracteristicas', form)
        .then(response => {
          console.log(response.data.data)
          commit('UPDATE_INV_CARACTERISTICAS', response.data.data)
          Vue.swal({
            type: 'success',
            icon: 'success',
            title: 'Exitoso!',
            text: 'Registro agregado correctamante !!'
          })
        })
        .catch(error => {
          if (error == null) {
            Vue.swal({
              type: 'error',
              title: 'Ooops',
              text: 'Problemas con el servidor, contactar al de sistema !!'
            })
          }
        })
    }
  }
}

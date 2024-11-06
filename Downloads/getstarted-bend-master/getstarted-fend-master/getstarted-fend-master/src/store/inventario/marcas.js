
import axios from 'axios'
import Vue from 'vue'

export default {
  state: {
    marca: {},
    dialog_form: {
      dialog: false,
      title: '',
      body: {}
    },
    marcas: [],
    marcasModelos: []
  },
  getters: {
    get_marcas (state) {
      return state.marcas
    },
    get_marca (state) {
      return state.marca
    },
    get_marcasModelos (state) {
      return state.marcasModelos
    }
  },
  mutations: {
    OPEN_DIALOG_FORM (state, data) {
      state.dialog_form.dialog = true
      state.dialog_form.title = data.title
      state.dialog_form.body = data.body
    },
    CLOSE_DIALOG_FORM (state) {
      state.dialog_form.body = {}
      state.dialog_form.dialog = false
    },
    UPDATE_MARCAS (state, newMarca) {
      state.marcas.push(newMarca)
    },
    GET_MARCAS (state, marcas) {
      state.marcas = []
      state.marcas.push(...marcas)
    },
    POST_MARCA (state, marca) {
      state.marcas.push(marca)
      state.marca = marca
    },
    GET_MARCAS_MODELOS (state, marcaModelos) {
      state.marcasModelos = []
      state.marcasModelos.push(...marcaModelos)
    }
  },
  actions: {
    getMarcas ({ commit }) {
      axios.get('/marcas')
        .then(response => {
          commit('GET_MARCAS', response.data)
        })
        .catch(error => {
          if (error == null) {
            Vue.swal({
              type: 'error',
              title: 'Ooops!',
              text: 'Problemas con el servidor, contactar al sistemas !!'
            })
          }
        })
    },
    getMarcasModelos ({ commit }) {
      axios.get('/catalogo/marcas/modelos')
        .then(response => {
          commit('GET_MARCAS_MODELOS', response.data)
        })
        .catch(error => {
          if (error == null) {
            Vue.swal({
              type: 'error',
              title: 'Ooops!',
              text: 'Problemas con el servidor, contactar al sistemas !!'
            })
          }
        })
    },
    postMarca ({ commit }, marca) {
      axios.post('/catalogo/marcas', marca)
        .then(response => {
          console.log('Respuesta', response.data)
          commit('POST_MARCA', response.data)
        })
        .catch(error => {
          if (error == null) {
            Vue.swal({
              type: 'error',
              title: 'Ooops!',
              text: 'Problemas con el servidor, contactar al sistemas !!'
            })
          }
        })
    }

  }
}

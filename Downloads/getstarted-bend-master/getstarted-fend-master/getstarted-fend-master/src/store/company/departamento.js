import axios from 'axios'
import Vue from 'vue'

export default {
  state: {
    departamentos: [],
    departamentosChildren: []
  },
  getters: {
    get_departamentos (state) {
      return state.departamentos
    },
    get_departamentosChildren (state) {
      return state.departamentosChildren
    }

  },
  mutations: {
    GET_DEPARTAMENTOS (state, departamentos) {
      state.departamentos = []
      state.departamentos.push(...departamentos)
    },
    GET_DEPARTAMENTOS_CHILDREN (state, departamentos) {
      state.departamentosChildren = []
      state.departamentosChildren.push(...departamentos)
    }
  },
  actions: {
    getDepartamentos ({ commit }) {
      axios.get('departamentos')
        .then(response => {
          commit('GET_DEPARTAMENTOS', response.data)
        })
        .catch(error => {
          if (error != null) {
            Vue.swal({
              type: 'error',
              title: 'Ooops!',
              text: 'Problemas en obterner y listar los departamentos, contactar al de sistemas !!'
            })
          }
        })
    },
    getDepartamentosChildren ({ commit }) {
      axios.get('departamentos/children')
        .then(response => {
          commit('GET_DEPARTAMENTOS_CHILDREN', response.data)
        })
        .catch(error => {
          if (error != null) {
            Vue.swal({
              type: 'error',
              title: 'Ooops!',
              text: 'Problemas en obterner y listar los departamentos, contactar al de sistemas !!'
            })
          }
        })
    }

  }
}

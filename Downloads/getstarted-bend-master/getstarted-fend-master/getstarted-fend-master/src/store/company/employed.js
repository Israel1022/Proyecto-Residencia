
import axios from 'axios'
import Vue from 'vue'

export default {
  state: {
    employees: null
  },
  getters: {
    get_employees (state) {
      return state.employees
    }
  },
  mutations: {
    GET_EMPLOYEES (state, employees) {
      state.employees = []
      state.employees.push(...employees)
    }
  },
  actions: {
    getSearchEmployeesByNames ({ commit }, names) {
      axios.get(`empleados/${names}`)
        .then(response => {
          commit('GET_EMPLOYEES', response.data)
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

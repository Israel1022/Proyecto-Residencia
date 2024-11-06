import axios from 'axios'

export default ({
    state: {
        getObject: {}
    },
    getters: {
        get_getObject(state) { return state.getObject }
    },
    mutations: {
        GET_RESPONSE_OBJECT (state, data) {
            state.getObject = {}
            state.getObject = data
        }
    },
    actions: {
        GetObject ({ commit }, data) {
            axios.get(data.url).then(response => {
                commit('GET_RESPONSE_OBJECT', response.data)
            })
        }
    }
})
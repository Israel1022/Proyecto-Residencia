import axios from 'axios'

export default ({
    state: {
        postObject: {}
    },
    getters: {
        get_object(state) { return state.postObject }
    },
    mutations: {
        POST_RESPONSE_OBJECT (state, data) {
            state.postObject = {}
            state.postObject = data
        }
    },
    actions: {
        PostObject ({ commit }, data) {
            axios.post(data.url,data.data).then(response => {
                commit('POST_RESPONSE_OBJECT', response.data)
            })
        }
    }
})
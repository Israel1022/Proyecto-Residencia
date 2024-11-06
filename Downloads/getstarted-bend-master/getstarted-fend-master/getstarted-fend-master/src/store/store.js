import { createStore } from "vuex"

import Forms from './modules/forms'
import Filters from './modules/filters'
import Dialogs from './modules/dialogs'
import Gets from './modules/methds/gets'
import Posts from './modules/methds/posts'

const store = createStore(
  {
    state: {},
    getters: {},
    mutations: {},
    actions: {},
    modules: {
      Forms,
      Filters,
      Dialogs,
      Gets,
      Posts
    }
  }
);
export default store
<template>
  <v-dialog v-model="get_MainProgressDialog.dialog"
    :max-width="get_MainProgressDialog.with" persistent>
    <v-list class="py-2" color="primary" elevation="12" rounded="lg">
      <v-list-item :prepend-icon="get_MainProgressDialog.icon" :title="get_MainProgressDialog.title">

        <template v-slot:prepend>
          <div class="pe-4">
            <v-icon color="primary" size="x-large"></v-icon>
          </div>
        </template>

        <template v-slot:append>
          <v-progress-circular
            color="primary" indeterminate="disable-shrink" size="16" width="2">
          </v-progress-circular>
        </template>
      </v-list-item>
    </v-list>
  </v-dialog>
</template>
<script>

import { mapGetters, mapMutations } from 'vuex'
export default {
  data: () => ({
  }),
  components: {},
  computed: {
    ...mapGetters(['get_MainProgressDialog'])
  },
  methods: {
    ...mapMutations(['HIDDEN_MAIN_PROGRESS_DIALOG'])
  },
}
  </script>
  <style scoped>
  </style>
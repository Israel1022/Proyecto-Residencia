<template>
  <v-dialog v-model="get_MainMessageDialog.dialog"
    :max-width="get_MainMessageDialog.with" persistent>
    <v-card>
        <v-card-item>
          <v-card-title class="text-center font-weight-black text-h5 pt-3">{{get_MainMessageDialog.title}}</v-card-title>
          <v-card-subtitle class="text-center font-weight-black text-body-1 pt-2">{{get_MainMessageDialog.subtitle}}</v-card-subtitle>
        </v-card-item>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn v-if="get_MainMessageDialog.body.showCancel"
            class="mx-3" color="red-darken-3" variant="outlined"
            :text="get_MainMessageDialog.body.nameCancel" @click="ConfirmMessage">
          </v-btn>
          <v-btn v-if="get_MainMessageDialog.body.showComfirm"
            class="mx-3" color="green-darken-3" variant="outlined"
            :text="get_MainMessageDialog.body.nameComfirm" @click="HIDDEN_MAIN_MESSAGE_DIALOG()">
          </v-btn>
        </v-card-actions>
      </v-card>
  </v-dialog>
</template>
<script>

import { mapGetters, mapMutations } from 'vuex'
export default {
  data: () => ({
  }),
  components: {},
  computed: {
    ...mapGetters(['get_MainMessageDialog'])
  },
  methods: {
    ...mapMutations(['HIDDEN_MAIN_MESSAGE_DIALOG', 'CONFIRM_MAIN_MESSAGE_DIALOG']),
    ConfirmMessage () {
      this.HIDDEN_MAIN_MESSAGE_DIALOG()
      this.CONFIRM_MAIN_MESSAGE_DIALOG({ identity: this.get_MainMessageDialog.identity, data: this.get_MainMessageDialog.data })
    }
  },
}
  </script>
  <style scoped>
  </style>
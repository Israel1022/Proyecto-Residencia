<template>
  <v-dialog v-model="get_MainFormDialog.dialog" persistent
    transition="dialog-bottom-transition" justify-center
    :max-width="get_MainFormDialog.with">
    <v-card>
      <v-toolbar :title="get_MainFormDialog.title">
        <v-spacer></v-spacer>
        <v-btn icon="mdi-close" @click="HIDDEN_MAIN_FORM_DIALOG()"></v-btn>
      </v-toolbar>
      <v-card-text class="pa-4">
      </v-card-text>
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
    ...mapGetters(['get_MainFormDialog'])
  },
  methods: {
    ...mapMutations(['HIDDEN_MAIN_FORM_DIALOG'])
  }
}
</script>
<style scoped>
</style>

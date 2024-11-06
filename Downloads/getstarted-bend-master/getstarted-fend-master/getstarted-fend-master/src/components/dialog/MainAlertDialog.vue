<template>
  <v-dialog persistent v-model="get_alertMain.alert" :max-width="get_alertMain.withWindows" justify-center>
    <v-toolbar dense dark color="blue darken-2">
      <v-toolbar-title> <h3>{{ get_alertMain.title }}</h3></v-toolbar-title>
      <v-spacer/>
      <v-btn icon dark @click="cancel">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-toolbar>
    <v-card tile>
      <v-card-text>
        <v-row dense class="py-3">
          <v-col cols="12" class="pa-4">
            <div class="black--text title text-center">

            </div>
            <div class="black--text title text-center">
                <p class="text-center ma-auto text-md-h4 text-sm-h5">
                    <label class="black--text font-weight-bold">{{ get_alertMain.body.message }}</label>
                </p>
                <p class="text-center ma-auto title">
                    <label class="black--text font-weight-bold">{{ get_alertMain.body.message }}</label>
                </p>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions align="bottom">
        <v-layout justify-end>
          <v-btn class="mr-3" color="red darken-1" depressed outlined @click="cancel">
            {{ (get_alertMain.cancelBtnText) ? get_alertMain.cancelBtnText : 'Cerrar' }}
          </v-btn>
          <v-btn class="mr-3" color="success darken-1" depressed outlined @click="send">
            {{ (get_alertMain.confirmBtnText) ? get_alertMain.confirmBtnText : 'Guardar' }}
          </v-btn>
        </v-layout>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
export default {
  data: () => ({}),
  computed: {
    ...mapGetters(['get_alertMain', 'get_model'])
  },
  watch: {
    get_model (val) {
      if (this.get_alertMain.type === 'delete') {
        this.DELETE_DATA_OBJECT(val)
      } else {
        this.SET_DATA_OBJECT(val)
      }
      this.cancel()
    }
  },
  methods: {
    ...mapActions(['PUTExecuteService', 'DELETEExecuteService']),
    ...mapMutations(['HIDDE_DIALOG_ALERT_MAIN', 'DELETE_DATA_OBJECT', 'SET_DATA_OBJECT']),
    cancel () { this.HIDDE_DIALOG_ALERT_MAIN() },
    send () {
      if (this.get_alertMain.type === 'delete') {
        this.DELETEExecuteService({
          url: this.get_alertMain.body.url,
          replace: '/{id}',
          replace_data: `/${this.get_alertMain.body.objectMain.id}`
        })
      } else if (this.get_alertMain.type === 'disable') {
        this.PUTExecuteService({
          url: this.get_alertMain.body.url,
          replace: '/{id}',
          replace_data: `/${this.get_alertMain.body.objectMain.id}`
        })
      }
    }
  }
}
</script>

<style>
</style>

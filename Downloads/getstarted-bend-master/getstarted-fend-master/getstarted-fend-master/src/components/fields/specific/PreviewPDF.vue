<template>
  <v-card :loading="!showIframePdf" tile elevation="0">
    <v-card-text>
      <iframe v-if="showIframePdf"
        class="iframe-custom"
        :src=this.pdfPage
        frameborder="0"
        scrolling="no"
        allowfullscreen>
      </iframe>
    </v-card-text>
  </v-card>

</template>

<script>
import axios from 'axios'
import Vue from 'vue'
// import { HeaderFileGral2 } from '@/store/modules/config'
export default {
  props: ['itemAction', 'setterModel', 'onSuccess'],
  data: () => ({
    ruta: '',
    objet: null,
    pdfPage: null,
    showIframePdf: false
  }),
  components: {},
  created () {
    // console.log(this.itemAction)
    if (this.setterModel) {
      this.ruta = this.setterModel[this.itemAction.nameid]
      this.objet = this.setterModel[this.itemAction.params]
      this.getDinamicData(this.ruta)
    }
  },
  methods: {
    getDinamicData (url) {
      const token = Vue.$cookies.get('token')
      const header = { Authorization: `Bearer ${token}` }
      axios.post(url, this.objet, { headers: header }).then((response) => {
        // decode base64 string, remove space for IE compatibility
        var binary = atob(response.data.pdf.replace(/\s/g, ''))
        var len = binary.length
        var buffer = new ArrayBuffer(len)
        var view = new Uint8Array(buffer)
        for (var i = 0; i < len; i++) {
          view[i] = binary.charCodeAt(i)
        }
        const blob = new Blob([view], { type: 'application/pdf' })
        this.pdfPage = URL.createObjectURL(blob)
        this.showIframePdf = true
      }).catch((error) => {
        if (error == null) console.log('Error')
      }).finally(() => {
      })
    }
  }
}
</script>
<style scoped>
.iframe-custom {
  width: 100%;
  height: 70vh;
}
</style>

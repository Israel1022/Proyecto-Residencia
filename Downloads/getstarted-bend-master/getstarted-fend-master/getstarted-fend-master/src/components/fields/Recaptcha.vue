<template>
     <VueRecaptcha :sitekey="this.sitekey" :loadRecaptchaScript="true" @verify="validate"/>
</template>
<script>
import VueRecaptcha from 'vue-recaptcha'
export default {
  props: ['onSuccess'],
  data: () => ({
    sitekey: process.env.VUE_APP_RECAPTCHA,
    vueWidgtId: 0
  }),
  components: {
    VueRecaptcha
  },
  mounted () {
    console.log('Valor de VUE_APP_RECAPTCHA:', this.sitekey)
  },
  methods: {
    validate (response) {
      this.onSuccess(response)
    }
  }
}
</script>

<style>

</style>

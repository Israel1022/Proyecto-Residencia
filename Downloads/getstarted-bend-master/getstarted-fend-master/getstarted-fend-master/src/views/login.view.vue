<template>
  <v-container>
    <v-layout>
      <loginform></loginform>
    </v-layout>
  </v-container>
</template>

<script>
import loginform from '@/components/login'

export default {
  data: () => ({}),
  components: {
    loginform
  }
}
</script>

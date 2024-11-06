<template>
  <v-container fluid fill-height>
    <div class="d-flex justify-center align-center h-100">
      <v-form class="px-8">
        <img src="@/assets/logo.png" class="team-img" />
        <h1 class="py-8 font-weight-bold text-center">Sign in</h1>

        <v-text-field prepend-inner-icon="mdi-account" placeholder="Username" variant="solo"/>
        <v-text-field prepend-inner-icon="mdi-lock" placeholder="Password" variant="solo" type="password" filled/>
        <v-btn color="info" class="pa-6 font-weight-bold" elevation="0" block dark tile @click="login()">Sign In</v-btn>
      </v-form>
    </div>
  </v-container>
</template>

<script>
import router from '@/router';
export default {
  data: () => ({}),
  methods: {
    login () {
      router.push({ name: 'DashboardPage' })
    }
  },
  components: {}
}
</script>

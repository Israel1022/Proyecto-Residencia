<template>
  <v-app id="login" class="blue darken-2">
    <v-container fluid fill-height>
      <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4 lg4>
          <v-card class="elevation-4 pa-6">

            <v-card-text>
                <ImageVie v-if="srcimg" :imageName="srcimg" width="75%" />
                <!-- <img :src="srcimg" alt="Vue Material Admin" width="75%" /> -->
              <v-form ref="form">
                <v-text-field
                  v-model="login.usuario"
                  label="Usuario"
                  hint="Ingrese su Usuario o Correo"
                  :rules="fieldRequired"
                  required/>
                <v-text-field
                  v-model="login.password"
                  label="Contraseña"
                  :append-icon="showeye ? 'visibility' : 'visibility_off'"
                  :append-icon-cb="() => (value = !value)"
                  :rules="fieldRequired"
                  :type="showeye ? 'text' : 'password'"
                  @click:append="showeye = !showeye"
                  @keyup.enter="loginAccess"
                  required/>
              </v-form>
              <Recaptcha v-if="showCapcha" :on-success="submit" />

            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success"  @click="loginAccess">Iniciar Sesion</v-btn>
            </v-card-actions>
            <v-progress-linear
              :active="loading"
              :indeterminate="true"
              absolute
              bottom
              color="blue darken-2 accent-4">
            </v-progress-linear>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
import router from '@/router'
import { Validate } from '@/mixins/validateFormMixin'
import ImageVie from '@/components/fields/ImageView'
import Recaptcha from '@/components/fields/Recaptcha'
export default {
  name: 'login',
  mixins: [Validate],
  props: {
    source: String
  },
  data: () => ({
    login: {},
    srcimg: 'logo_login.png',
    showeye: false,
    loading: false,
    showCapcha: true
  }),
  components: {
    ImageVie,
    Recaptcha
  },
  beforeMount () {
    this.loadImage()
  },
  mounted () {},
  methods: {
    ...mapActions(['getUrlServices', 'POSTObject']),
    loginAccess () {
      if (this.$refs.form.validate()) {
        this.sendLogin('usuario.login')
        this.loading = true
      } else {
        this.$swal({
          type: 'error',
          icon: 'error',
          title: '¡Error!',
          text: 'Campos Requeridos'
        })
      }
    },
    sendLogin (url) {
      if (this.get_urls != null) {
        const router = this.get_urls[url]
        this.POSTObject({ url: router, data: this.login })
      } else {
        this.getUrlServices()
      }
    },
    submit (results) {
      this.login.captcha = results
    },
    loadImage () {
      if (this.$cookies.isKey('logos')) {
        const l = this.$cookies.get('logos')
        this.srcimg = l.logo
      }
    },
    ...mapMutations(['CLEAR_OBJECT'])
  },
  computed: {
    ...mapGetters(['get_urls', 'get_object'])
  },
  watch: {
    get_urls (val) {
      if (val) {
        this.sendLogin('usuario.login')
      }
    },
    get_object (obj) {
      this.loading = false
      this.showCapcha = false
      if (obj) {
        if (obj.message || obj.error) {
          this.$swal({
            type: 'error',
            icon: 'error',
            title: '¡Error!',
            text: (obj.message) ? obj.message : obj.error
          })
          setTimeout(() => { this.showCapcha = true }, 500)
          return
        }
        this.$cookies.set('token', obj.access_token)
        const user = {
          usuario_id: obj.user.id,
          usuario: obj.user.usuario,
          empleado_id: obj.user.empleado_id,
          NombreCompleto: obj.user.empleado.NombreCompleto,
          nombres: obj.user.empleado.nombres,
          apellidos: obj.user.empleado.apellidos,
          cve_empleado: obj.user.empleado.cve_empleado,
          departamento_id: obj.user.empleado.departamento.id,
          departamento: obj.user.empleado.departamento.nombre,
          empresa_id: obj.user.empleado.departamento.empresa_id
        }
        this.$cookies.set('user', user)
        localStorage.setItem('permisos', JSON.stringify(obj.user.roles[0]))
        // localStorage.setItem('user', JSON.stringify(val.user))
        setTimeout(() => { this.showCapcha = true }, 500)
        router.push({ name: 'home' })
      }
    }
  },
  beforeDestroy () {
    this.CLEAR_OBJECT()
  }
}
</script>

<style scoped lang="css">
  #login {
    height: 50%;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    content: "";
    z-index: 0;
  }
  .verde{
    background: #009B37;
  }
</style>

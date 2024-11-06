  <template>
  <v-navigation-drawer v-model="toggle" app>
    <div class="user-info">
      <div class="image">
        <img src="@/assets/images/user.png" width="48" height="48" alt="User"/>
      </div>
      <div class="info-container  pt-3">
        <div class="email">{{nameSub}}</div>
        <div class="email"> depto: {{departamento}}</div>
        <!--<div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span> {{userName}}</span>
        </div>
        -->
      </div>
    </div>

    <v-list>
      <v-list-item @click="menu('home')">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-subtitle>Inicio</v-list-item-subtitle>
      </v-list-item>

      <span v-for="(action, index) in menuGral" :key="index">
        <ListGroupitem v-if="action.group" :item="action" :on-success="ItemAction" />
        <ListItem v-else :item="action" :on-success="ItemAction" main="si" />
      </span>

      <v-divider />

      <v-list-item @click="menu('logout')">
        <v-list-item-action>
          <v-icon class="blanco">mdi-logout</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title class="blanco">Cerrar Sesión</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

    </v-list>
  </v-navigation-drawer>
</template>
<!-- ,default: false, required: false -->
<script>
import { mapActions, mapGetters } from 'vuex'
import router from '@/router'
import ListItem from '@/components/fields/ListItem.vue'
import ListGroupitem from '@/components/fields/ListGroupitem.vue'

export default {
  props: {
    toggle: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  data: () => ({
    userName: '',
    nameSub: '',
    departamento: '',
    clipped: false,
    permisos: null,
    permisosUser: false,
    multiple: true,
    focusable: true,
    menuGral: [
      {
        id: 'mesa-ayuda.show-pagination',
        action: 'MeAyuSolicitudes',
        icon: 'mdi-animation',
        title: 'Seguimiento',
        group: false,
        hidden: 'hiddenItem'
      },
      /*
      {
        id: 'mesa-ayuda.show-Transporte',
        action: 'MeAyuSolicitudesTransportes',
        icon: 'mdi-animation',
        title: 'Solicitudes Transporte',
        group: false,
        hidden: 'hiddenItem'
      },
      */
      {
        id: 'catalogos',
        icon: 'mdi-book-multiple',
        group: true,
        title: 'Catálogos',
        hidden: 'hiddenItem',
        items: [
          {
            id: 'catalogo.tecnologias.show',
            action: 'TecnologiasView',
            icon: 'mdi-monitor',
            title: 'Tecnologias y servicios de la informacion',
            group: false,
            hidden: 'hiddenItem'
          },
          {
            id: 'catalogo.comunicacionsocial.show',
            action: 'ComunicacionsocialView',
            icon: 'mdi-account-multiple',
            title: 'Comunicacion social y relaciones publicas',
            group: false,
            hidden: 'hiddenItem'
          },
          {
            id: 'empresa.empleados.show',
            action: 'ConfigEmpleadosView',
            icon: 'mdi-account-group',
            title: 'Empleados',
            hidden: 'hiddenItem'
          },
          {
            id: 'empresa.departamentos.show',
            action: 'DepartamentoView',
            icon: 'mdi-lan',
            title: 'Departamentos',
            hidden: 'hiddenItem'
          }
        ]
      },

      {
        id: 'configuraciones',
        icon: 'mdi-cogs',
        group: true,
        title: 'Configuraciones',
        hidden: 'hiddenItem',
        items: [
          {
            id: 'empresa.usuarios.show',
            action: 'ConfigAuthUsersView',
            icon: 'mdi-account-circle',
            title: 'Usuarios',
            hidden: 'hiddenItem'
          },
          {
            id: 'empresa.roles.show',
            action: 'ConfigAuthRolsView',
            icon: 'mdi-account-network',
            title: 'Roles',
            hidden: 'hiddenItem'
          },
          {
            id: 'roles.permisos.show',
            action: 'ConfigAuthPermissView',
            icon: 'mdi-key',
            title: 'Permisos',
            hidden: 'hiddenItem'
          }
        ]
      }
    ]
  }),
  components: { ListItem, ListGroupitem },
  mounted () {
    this.PermisosMenu(JSON.parse(localStorage.getItem('permisos')))
    this.setDataUser()
  },
  methods: {
    ...mapActions(['getUrlServices', 'POSTObjectLogout']),
    ItemAction (item) { router.push({ name: item.action }).catch(() => {}) },
    setDataUser () {
      const user = this.$cookies.get('user')
      this.userName = user.usuario
      this.nameSub = user.NombreCompleto
      this.departamento = user.departamento
    },
    menu (item) {
      if (item != null) {
        if (item === 'logout') {
          this.$swal
            .fire({
              title: '¿Desea cerrar su sesión?',
              text: 'Asegúrese de haber concluido sus labores diarias',
              icon: 'warning',
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Cerrar sesión',
              cancelButtonText: 'Cancelar'
            })
            .then(result => {
              if (result.value) {
                this.getUrl('usuario.logout')
              }
            })
        } else {
          router.push({ name: item }).catch(() => {})
        }
      }
    },
    PermisosMenu (user) {
      if (user.descripcion.special === 'all-access') {
        this.menuGral.map(action => {
          if (action.group) {
            action.hidden = ''
            action.items.map(subItems => {
              if (subItems.group) {
                subItems.hidden = ''
                subItems.items.map(subsubItems => {
                  subsubItems.hidden = ''
                })
              } else {
                subItems.hidden = ''
              }
            })
          } else {
            action.hidden = ''
          }
        })
        return
      }
      this.menuGral.map(action => {
        user.permisos.map(item => {
          if (action.group) {
            if (item.slug === action.id) {
              action.hidden = ''
              action.items.map(subItems => {
                user.permisos.map(per => {
                  if (subItems.group) {
                    if (per.slug === subItems.id) {
                      subItems.hidden = ''
                      subItems.items.map(subsubItems => {
                        user.permisos.map(persub => {
                          if (persub.slug === subsubItems.id) {
                            subsubItems.hidden = ''
                          }
                        })
                      })
                    }
                  } else {
                    if (per.slug === subItems.id) {
                      subItems.hidden = ''
                    }
                  }
                })
              })
            }
            // action para los sub menu
          } else {
            if (item.slug === action.id) {
              action.hidden = ''
            }
          }
        })
      })
    },
    getUrl (objet) {
      if (this.get_urls != null) {
        const router = this.get_urls[objet]
        this.POSTObjectLogout({ url: router, data: {} })
      } else {
        this.getUrlServices()
      }
    }
  },
  computed: {
    ...mapGetters(['get_urls', 'get_object_logout'])
  },
  watch: {
    get_object_logout (val) {
      if (val.message) {
        this.$swal({
          type: 'error',
          icon: 'error',
          title: 'Error!',
          text: val.message
        })
        return
      }
      this.$cookies.remove('user')
      this.$cookies.remove('token')
      localStorage.clear()
      router.push('/')
    }
  }
}
</script>

<style>
.user-info {
  padding: 13px 15px 12px 15px;
  white-space: nowrap;
  position: relative;
  border-bottom: 1px solid #e9e9e9;
  background: url("../../assets/images/horizontal.jpg") no-repeat no-repeat;
  height: 135px;
}
.user-info .image {
  margin-right: 12px;
  display: inline-block;
}
.user-info .image img {
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  border-radius: 50%;
  vertical-align: bottom !important;
}
.user-info .info-container .name {
  white-space: nowrap;
  -ms-text-overflow: ellipsis;
  -o-text-overflow: ellipsis;
  text-overflow: ellipsis;
  overflow: hidden;
  font-size: 14px;
  max-width: 200px;
  color: #fff;
}
.user-info .info-container .email {
  white-space: nowrap;
  -ms-text-overflow: ellipsis;
  -o-text-overflow: ellipsis;
  text-overflow: ellipsis;
  overflow: hidden;
  font-size: 12px;
  max-width: 200px;
  color: #fff;
}
.hiddenItem{
  display: none;
}
</style>

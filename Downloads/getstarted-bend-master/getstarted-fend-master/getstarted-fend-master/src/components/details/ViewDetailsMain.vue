<template>
  <component :is="type" :model="object" :on-success="send" />
</template>

<script>
import UsuarioDetails from '@/components/details/acceso/UsuarioDetails'
import RolPermisosDetails from '@/components/details/acceso/RolPermisosDetails'
import EquipoDetails from '@/components/details/EquipoDetails.vue'
import ProcesosDetail from '@/components/details/ProcesosDetail.vue'
export default {
  components: {
    EquipoDetails,
    UsuarioDetails,
    RolPermisosDetails,
    ProcesosDetail
  },
  props: ['object', 'type', 'actions'],
  data: () => ({}),
  methods: {
    send (act) {
      if (this.actions) this.actions(act)
    }
  }
}
</script>
<style>
</style>

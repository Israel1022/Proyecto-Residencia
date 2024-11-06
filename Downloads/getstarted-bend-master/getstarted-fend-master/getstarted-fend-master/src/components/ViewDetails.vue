<template>
<v-container fluid>
  <v-layout justify-center>
    <v-flex md12>
        <Solicitud v-if="type == 'Solicitud'" :model="object" :on-success="eventDetails"/>
        <HistoryMovements v-if="type == 'Movements'" :model="object"/>
        <ProcesosDetail v-if="type == 'DetailProcesos'" :model="object"/>
        <EquipoDetail v-if="type == 'DetailEquipo'" :model="object" :on-success="eventDetails"/>
    </v-flex>
  </v-layout>
</v-container>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
import Solicitud from '@/components/details/SolicitudDetail'
import HistoryMovements from '@/components/details/HistoryMovements'
import EquipoDetail from '@/components/details/EquipoDetails'
import ProcesosDetail from '@/components/details/ProcesosDetail'
export default {
  props: ['object', 'type', 'actions'],
  data: () => ({
  }),
  mounted () {
  },
  methods: {
    ...mapActions([]),
    eventDetails (item) {
      this.actions(item)
    },
    ...mapMutations([])
  },
  computed: {
    ...mapGetters([])
  },
  components: {
    Solicitud,
    HistoryMovements,
    EquipoDetail,
    ProcesosDetail
  },
  watch: {
  },
  beforeDestroy () {
  }

}
</script>

<style>

</style>

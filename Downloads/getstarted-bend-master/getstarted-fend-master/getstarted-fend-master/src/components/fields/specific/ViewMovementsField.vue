<template>
  <v-card class="elevation-2">
    <v-card-text>
      <v-timeline align-top dense class="scroll-box">
          <v-timeline-item v-for="(movement, index) in movements" :key="index" :color="movement.estatus.color" small>
          <div>
              <div class="font-weight-normal">
                <v-chip :color="movement.estatus.color" label outlined>{{ movement.estatus.estatus }}</v-chip>
                @ <label class="font-weight-bold subtitle-2 text--primary">
                    {{ new Date(movement.created_at).toISOString().substr(0, 10) }}
                    {{ new Date(movement.created_at).toLocaleTimeString('it-IT') }}
                  </label>
              </div>
              <div v-if="movement.detalle" class="pl-1">
                <p class="font-italic ma-0" v-for="(field, index) in movement.estatus.formulario.fields" :key="index">
                      <span class="font-weight-bold font-weight-black"> {{ field.name }}:</span>
                      <span v-if="field.nameid == 'empleado_id'">
                          {{ movement.detalle.descripcion[field.name.toLowerCase()].NombreCompleto }}
                      </span>
                      <span v-else-if="movement.detalle.descripcion[field.name.toLowerCase()]">
                          {{ movement.detalle.descripcion[field.name.toLowerCase()] }}
                      </span>
                      <span v-else>
                          {{ movement.detalle.descripcion[field.nameid.toLowerCase()] }}
                      </span>
                </p>
              </div>
          </div>
          </v-timeline-item>
      </v-timeline>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'
import { HeaderGral } from '@/store/modules/config'
export default {
  props: ['itemAction', 'onSuccess'],
  data: () => ({
    movements: []
  }),
  components: {},
  created () {
    console.log('itemAction', this.itemAction.url)
    if (this.itemAction.url) { this.getDinamicData(this.itemAction.url) }
    // this.getDinamicData(this.itemAction.url)
  },
  methods: {
    getDinamicData (url) {
      const header = HeaderGral()
      axios.get(url, header).then((response) => {
        this.movements = []
        this.movements.push(...response.data)
      }).catch((error) => {
        if (error == null) console.log('Error')
      }).finally(() => {
      })
    }
  }
}
</script>

<style scoped>
.scroll-box {
  min-height: 300px;
  max-height: 1000px;
  width: 100%;
  overflow-x: auto;
}
</style>

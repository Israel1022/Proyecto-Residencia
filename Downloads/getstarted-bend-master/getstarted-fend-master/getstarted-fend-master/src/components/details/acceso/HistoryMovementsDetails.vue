<template>
  <v-card v-if="model.id || model.cve_empleado" class="elevation-2">
    <v-card-title class="text-h5 text-sm-h4 text-uppercase">
      <label class="font-weight-bold text--primary ma-auto text-center">Historial de Movimientos</label>
    </v-card-title>
    <v-card-text class="scroll-box">
      <v-timeline align-top dense>
        <v-timeline-item v-for="(movement, index) in model.movements" :key="index" :color="movement.estatus.color" small>
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
                <span v-if="containsString('_id', field.nameid)">
                  {{ setNameObject(movement.detalle.descripcion, field.nameid) }}
                </span>
                <span v-else>
                  <!-- {{ movement.detalle.descripcion[field.name.toLowerCase()] }} -->
                  {{ movement.detalle.descripcion[field.nameid] }}
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
export default {
  name: 'HistoryMovementsDetails',
  props: ['model'],
  data: () => ({}),
  methods: {
    containsString(find, text) {
      if (text) {
        const resultado =  text.includes(find)
        return resultado
      }
      return false
    },
    setNameObject (descripcion, nameid){
      const object = descripcion[ nameid.replace('_id','')]
      if (object) {
        if (nameid === 'empleado_id')  return object.NombreCompleto
        return object.nombre
      }
      return '-- --'
    }
  }
}
</script>

<style>
.scroll-box {
  height: 500px;
  overflow-x: auto;
}
</style>

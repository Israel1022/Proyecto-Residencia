<template>
  <v-card v-if="model.id" class="elevation-2">
    <v-card-title class="text-h5">
      <h2 class="font-weight-bold text--primary">Historial</h2>
    </v-card-title>
    <v-card-text>
        <v-timeline align-top dense class="scroll-box">
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
                  <p v-if="movement.detalle.descripcion.insumos">
                    <span class="font-weight-bold font-weight-black subtitle-1"> Insumos :</span><br/>
                    <v-chip v-for="(insumo,index) in movement.detalle.descripcion.insumos" :key="index"
                      class="ma-2" color="cyan" outlined>
                      <v-icon left>mdi-check-circle</v-icon>
                      {{ insumo.descripcion }} - Cant. {{ insumo.utiliza }}
                    </v-chip>
                  </p>
                </div>

                <div v-else class="pl-1 pt-1">
                    <p class="font-italic" v-if="model.empleado">
                        <span class="font-weight-bold font-weight-black">Empleado de Alta: </span>
                        {{ model.empleado.NombreCompleto }}
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
  props: ['model'],
  data: () => ({})
}
</script>

<style>
.scroll-box {
  height: 410px;
  overflow-x: auto;
}
</style>

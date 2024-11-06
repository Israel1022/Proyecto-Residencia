<template>
  <v-menu
    ref="menu"
    v-model="menu"
    :close-on-content-click="false"
    nudge-right="40"
    nudge-top="30"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template #activator="{ on, attrs }">
      <v-text-field
        v-model="dateMain"
        :label="itemAction.name"
        class="font-weight-bold black--text"
        prepend-inner-icon="mdi-calendar"
        v-bind="attrs"
        clearable
        readonly
        dense
        outlined
        @click:clear="clearData()"
        v-on="on"
      />
    </template>

    <v-date-picker
      v-model="dateMain"
      show-adjacent-months
      min="1950-01-01"
      year-icon="mdi-calendar-blank"
      prev-icon="mdi-skip-previous"
      next-icon="mdi-skip-next"
      locale="es"
      @change="save"
    />
  </v-menu>
</template>

<script>
import { DatesUtils } from '@/mixins/datesUtilsMixin'
export default {
  mixins: [DatesUtils],
  props: ['itemAction', 'setterModel', 'onSuccess'],
  data: () => ({
    dateMain: '',
    menu: false
  }),
  watch: {
    setterModel (val) {
      if (val) this.dateMain = val
    }
  },
  mounted () {
    this.seterModel(this.setterModel)
  },
  methods: {
    save (date) {
      this.$refs.menu.save(date)
      if (date != null) {
        this.onSuccess({ id: this.itemAction.nameid, data: date, fieldMain: this.itemAction })
      }
    },
    clearData () {
      this.dateMain = ''
      this.onSuccess({ id: this.itemAction.nameid, data: null })
    },
    setDefault () {
      const date = new Date()
      const fecha = this.formatoFecha(date, 'yyyy-mm-dd')
      this.dateMain = fecha
      this.onSuccess({ id: this.itemAction.nameid, data: fecha })
    },
    seterModel (val) {
      if (val) this.dateMain = val
      else if (this.itemAction.default) this.setDefault()
    }
  }
}
</script>

<style>
</style>

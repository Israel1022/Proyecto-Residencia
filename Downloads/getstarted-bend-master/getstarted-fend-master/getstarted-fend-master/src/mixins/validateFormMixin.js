export const Validate = {
  data () {
    return {
      fieldRequired: [value => !!value || 'Campo Requerido.'],
      numberRangeRule: v => {
        if (!v.trim()) return true
        if (!isNaN(parseFloat(v)) && v >= 0 && v <= 999) return true
        return 'Number has to be between 0 and 999'
      },
      emailRules: [
        v => !!v || 'Campo Requerido',
        v => /.+@.+\..+/.test(v) || 'Correo debe ser valido'
      ]
    }
  }
}

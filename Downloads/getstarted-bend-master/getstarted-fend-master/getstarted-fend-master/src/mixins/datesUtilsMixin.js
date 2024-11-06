export const DatesUtils = {
  data () {
    return {
      daysWeek: ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'],
      months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthsAbbreviation: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
    }
  },
  methods: {
    getDate () {
      const mydate = new Date()
      return mydate
    },
    dateStringToDate (date) {
      const parts = date.split('-')
      const mydate = new Date(parts[0], parts[1] - 1, parts[2])
      return mydate
    },
    datetimeStringToDate (date) {
      const mydate = new Date(date)
      return mydate
    },
    formatoFecha (fecha, formato) {
      const map = {
        dd: (fecha.getDate() < 10) ? `0${fecha.getDate().toString()}` : fecha.getDate().toString(),
        mm: ((fecha.getMonth() + 1) < 10) ? `0${(fecha.getMonth() + 1).toString()}` : (fecha.getMonth() + 1).toString(),
        yyyy: fecha.getFullYear()
      }
      return formato.replace(/dd|mm|yyyy/gi, (matched) => map[matched])
    },
    formatoFechaTitle (fecha) {
      if (!fecha) return
      const dayWeek = this.daysWeek[fecha.getUTCDay()]
      const day = fecha.getDate()
      const month = this.months[fecha.getMonth()]
      const year = fecha.getFullYear()
      return `${dayWeek} ${day} de ${month} ${year}`
    },
    formatoFechaAbreviacion (fecha) {
      const date = this.dateStringToDate(fecha.substring(0, 10))
      const day = (date.getDate() < 10) ? `0${date.getDate()}` : date.getDate()
      const month = this.monthsAbbreviation[date.getMonth()]
      const year = date.getFullYear()
      return `${day}-${month}-${year}`
    },
    AdditiontoDay (fecha, dias) {
      fecha = new Date((fecha.getTime() + (dias * 24 * 60 * 60 * 1000)))
      return fecha
    },
    compareDateEqual ({ f1, f2 }) {
      const date1 = this.dateStringToDate(f1.substring(0, 10))
      const date2 = this.dateStringToDate(f2.substring(0, 10))
      return (date1 > date2)
    },
    AgeBirthDate (date) {
      const hoy = new Date()
      const cumpleanos = new Date(date)
      let edad = hoy.getFullYear() - cumpleanos.getFullYear()
      const m = hoy.getMonth() - cumpleanos.getMonth()
      if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--
      }
      return `${edad} años`
    },
    getYear () {
      const year = new Date().toISOString().substr(0, 4)
      return year
    }
  }
}

<template>
  <v-dialog persistent v-model="get_dialogMain.dialog"
    :max-width="get_dialogMain.withWindows"
    transition="dialog-top-transition"
    justify-center>

    <v-toolbar dense dark color="blue darken-2">
      <v-btn v-if="showReturnForm" class="hidden-xs-only" @click="ReturnForm()" icon>
        <v-icon>mdi-arrow-left</v-icon>
      </v-btn>
      <v-toolbar-title> <h3>{{ get_dialogMain.title }}</h3></v-toolbar-title>
      <v-spacer/>
      <v-btn icon dark @click="HIDDE_DIALOG_MAIN()">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-toolbar>

    <v-card tile>
      <v-card-text>
        <v-row dense class="pt-3">
          <MainGeneralForm v-if="showFormSecound" :form="formSecound" :model="objectSecound" :on-success="save" :on-cancel="cancel" />
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
import MainGeneralForm from '@/components/forms/main/MainGeneralForm'
export default {
  data: () => ({
    model: null,
    params: null,

    showFieldActividad: false,
    ActividadSelected: 0,
    ActividadObjet: null,
    selectActividadField: {
      name: 'Tipo Actividad',
      nameid: 'tipo_actividad_id',
      object_name: 'tipo_actividad',
      url: '/empresa/tipo-actividades/show-solicitudes',
      value: 'id'
    },
    showFieldFamilia: false,
    FamiliaSelected: 0,
    selectFamiliaField: { name: 'Familia-SubFamilia', nameid: 'familia_id', url: '/catalogo/familias/subfamilias', params: '', showFamilia: false },

    showReturnForm: false,
    idMain: 0,

    showFormMain: false,
    formMain: {},
    objectMain: {},
    paramsDialog: {},
    updateMain: false,

    showFormSecound: false,
    formSecound: {},
    objectSecound: {},

    fields: {},
    UrlexecuteMain: null
  }),
  components: {
    MainGeneralForm
  },
  mounted () {
    this.UrlexecuteMain = null
    this.model = this.get_dialogMain.body.objectMain
    this.params = this.get_dialogMain.body.paramsDialog

    if (this.params.showActividad) {
      if (this.params.updated) {
        this.updateMain = this.params.updated
        this.objectMain = JSON.parse(JSON.stringify(this.model.descripcion))
        this.ActividadSelected = this.model.tipo_actividade_id
        this.showFieldActividad = true
      } else {
        this.showFieldActividad = this.params.showActividad
        if (this.params.setmodel) this.objectMain = JSON.parse(JSON.stringify(this.model))
      }
    } else if (this.params.showFamilia) {
      this.selectFamiliaField.params = (this.params.familiaParams) ? this.params.familiaParams : ''
      if (this.params.updated) {
        this.objectSecound = this.model.especificaciones
        this.FamiliaSelected = this.model.caracteristicas.familia_id
        this.showFieldFamilia = true
      } else {
        this.showFieldFamilia = true
        if (this.params.setmodel) this.objectMain = JSON.parse(JSON.stringify(this.model))
      }
    } else if (this.params.form) {
      this.formSecound = JSON.parse(JSON.stringify(this.params.form))
      if (this.params.setmodel) this.objectSecound = Object.assign({}, this.model)
      this.showFormSecound = true
    } else if (this.get_dialogMain.form === 'process') {
      this.showFormMain = true
      if (this.model.action) {
        if (this.params.setmodel) this.object = this.params.data
        this.formMain = JSON.parse(JSON.stringify(this.model.body))
        return
      }
      if (this.params.setmodel) this.object = this.model
      this.GETOptionService({ url: this.params.urloption, params: '/1', replace: '[/{status_id}]' })
    }
  },
  methods: {
    ...mapActions(['POSTExecuteService', 'GETOptionFormService', 'GETOptionService',
      'urlPostServicesAction', 'getFormServicesAction', 'urlGetServiceActionTwo', 'postExecuteAction']),
    ...mapMutations(['HIDDE_DIALOG_MAIN', 'CLEAR_DATA', 'SET_DATA_OBJECT', 'CLEAR_OBJECTS_MODEL']),
    ActionFieldData (obj) {
      this.showFormMain = true
      if (obj.id === 'tipo_actividad_id') {
        console.log('obj.data', obj)
        this.ActividadSelected = obj.data
        if (obj.data) {
          this.ActividadObjet = obj.objectdata
          const router = this.get_urls[obj.objectdata.permiso]
          this.GETOptionService({ url: router, params: '/1', replace: '[/{status_id}]' })
        } else {
          this.ActividadObjet = null
        }
      } else if (obj.id === 'familia_id') {
        this.FamiliaSelected = obj.data
        const router = this.get_urls['catalogo.familias-caracteristica.formulario']
        this.urlGetServiceActionTwo({ url: router, params: `/${obj.data}`, replace: '/{id}' })
      }
    },
    ReturnForm () {
      this.showReturnForm = false
      this.showFieldFamilia = true
      this.showFormMain = false
      this.showFormSecound = true
    },
    save (model) {
      if (this.params.showFamilia) {
        this.objectSecound = model
        if (this.params.updated) { this.objectMain = this.model.caracteristicas }
        this.showReturnForm = true
        this.showFieldFamilia = false
        this.showFormSecound = false
        this.GETOptionService({ url: this.params.urloption, params: '/1', replace: '[/{status_id}]' })
        return
      }
      if (!this.params.url) {
        this.onSuccess({ name: this.get_dialogMain.type, model: model })
        this.cancel()
        return
      }
      if (this.params.update) this.PUTExecuteService({ url: this.params.url, data: model })
      else this.POSTExecuteService({ url: this.params.url, data: model })
    },
    saveProcess (model) {
      if (this.model.item) model.id = this.model.item.id
      else if (this.model.id) model.id = this.model.id

      if (this.params.showActividad) {
        if (this.params.updated) model.action = 'toUpdate'
        model.tipo_actividad_id = this.ActividadSelected
        this.UrlexecuteMain = this.get_urls[this.ActividadObjet.accion]
      } else if (this.params.showFamilia) {
        if (this.params.updated) model.action = 'toUpdate'
        model.descripcion.familia_id = this.FamiliaSelected
        model.caracteristica = this.objectSecound
        model.tipo_actividad_id = this.params.actividad
      }
      // console.log(model)
      // console.log(this.UrlexecuteMain)
      if (this.UrlexecuteMain) this.POSTExecuteService({ url: this.UrlexecuteMain, data: model })
      else this.POSTExecuteService({ url: this.params.url, data: model })
    },
    cancel () {
      this.HIDDE_DIALOG_MAIN()
    }
  },
  computed: {
    ...mapGetters(['get_dialogMain', 'get_urls', 'get_object', 'get_objectTwo', 'get_options', 'get_model'])
  },
  watch: {
    get_object (val) {
      if (val.message) {
        this.$swal({ type: 'error', icon: 'error', title: 'Error!', text: val.message })
        return
      }
      this.$swal({ type: 'success', icon: 'success', title: 'Exitoso!', text: this.params.message })
      if (this.params.returnObject) {
        this.onSuccess({ name: this.get_dialogMain.type, model: val })
        this.cancel()
        return
      } else if (this.get_dialogMain.body && this.get_dialogMain.body.content === 'form-data') {
        this.cancel()
        return
      }
      this.SET_DATA_OBJECT(val)
      this.cancel()
    },
    get_objectTwo (vals) {
      if (vals) {
        this.formSecound = vals.fields
        this.showFormSecound = true
      }
    },
    get_options (val) {
      if (this.params.showActividad) {
        this.formMain = val
        this.showFormMain = true
      } else if (this.params.showFamilia) {
        this.formMain = val
        if (this.params.process === 6) {
          this.formMain.status_finish.formulario.fields.map(fiel => {
            if (fiel.hidden) fiel.hidden = false
          })
        }
        this.showFormMain = true
      }
    },
    get_model (val) {
      if (val.message) {
        this.$swal({ type: 'error', icon: 'error', title: 'Error !', text: val.message })
        return
      }
      this.$swal({ type: 'succes', icon: 'success', title: 'Exitoso!', text: this.params.message, timer: 1500 })
      if (this.params.returnObject) {
        this.onSuccess({ name: this.get_dialogMain.type, model: val })
        this.cancel()
        return
      } else if (this.get_dialogMain.body && this.get_dialogMain.body.content === 'form-data') {
        this.cancel()
        return
      }
      this.SET_DATA_OBJECT(val)
      this.cancel()
    }
  },
  beforeDestroy () {
    this.CLEAR_OBJECTS_MODEL()
  }
}
</script>

<style></style>

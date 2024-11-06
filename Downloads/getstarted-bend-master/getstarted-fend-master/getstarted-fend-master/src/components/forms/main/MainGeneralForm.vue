<template>
  <v-card width="100%" tile elevation="0">
    <v-card-text class="py-1">
      <v-form ref="form">
        <v-row dense>
          <v-col v-for="(field, i) in form" :key="i" :cols="field.cols" :offset="field.offset" :hidden="field.hidden">
            <p v-if="field.field == 'messageViewText' && !field.hidden" :class="field.classMain">
              <label class="black--text font-weight-bold">{{ field.name }}</label>
            </p>
            <p v-if="field.field == 'viewTextList' && !field.hidden">
              <label class="font-weight-bold">{{field.name}} </label>
              <v-chip v-for="(item, index) in model[field.nameid]" :key="index"
                :color="field.color" class="ma-1" small label outlined>
              <span class="font-weight-bold text-uppercase">{{item[field.itemnameid]}}</span>
            </v-chip>
            </p>

            <v-text-field
              v-if="field.field == 'text' && !field.hidden"
              v-model.trim="model[field.nameid]"
              :label="field.name"
              :type="field.type"
              :rules="field.rules ? (field.countChar)? textRules : fieldRequired : []"
              outlined
              dense>
            </v-text-field>

            <v-text-field
              v-if="field.field == 'textEmail' && !field.hidden"
              v-model="model[field.nameid]"
              :label="field.name"
              :type="field.type"
              :rules="field.rules ? (field.countChar)? textRules : fieldRequired : []"
              :readonly="field.readonly"
              outlined
              dense
            />

            <v-text-field
              v-if="field.field == 'number' && !field.hidden"
              v-model.number="model[field.nameid]"
              :label="field.name"
              :type="field.type"
              min="0"
              :rules="field.rules ? fieldRequired : []"
              outlined
              dense>
            </v-text-field>

            <v-textarea
              v-else-if="field.field == 'textArea' && !field.hidden"
              v-model="model[field.nameid]"
              :label="field.name"
              :name="field.dimens"
              :rules="field.rules ? fieldRequired : []"
              outlined
              dense>
            </v-textarea>

            <v-select
              v-else-if="field.field == 'select' && !field.hidden"
              v-model="model[field.nameid]"
              :label="field.name"
              :items="field.items"
              :rules="field.rules ? fieldRequired : []"
              @change="RadioButtonEvent(field)"
              outlined
              dense
            >
            </v-select>

            <div v-else-if="field.field == 'radioButton' && !field.hidden">
            <label class="black--text font-weight-bold font-weight-black subtitle-2">{{field.name}}</label>
            <v-radio-group
              v-model="model[field.nameid]"
              :row="field.row"
              :rules="(field.rules)? fieldRequired : []"
              class="pa-1 ma-1"
              @change="RadioButtonEvent(field)"
            >
              <v-radio
                v-for="(value,index) in field.items"
                :key="index"
                :label="(value.name)? value.name : value"
                :value="(value.name)? value.value : value"
              />
            </v-radio-group>
          </div>

            <div v-else-if="field.field == 'checkbox'">
              <p class="ma-0 pa-0 text--black font-weight-bold font-weight-black subtitle-2"> {{ field.name }} </p>
              <v-checkbox
                v-for="value in field.items"
                :key="value"
                :class="field.row ? 'mr-3 pa-0 checkbook-horizontal' : 'ma-0 pa-0'"
                v-model="model[field.nameid]"
                :label="value"
                :value="value"
                :rules="field.rules ? fieldRequired : []">
              </v-checkbox>
            </div>

            <DatePicker
              v-else-if="field.field == 'textDatePicker' && !field.hidden"
              :itemAction="field"
              :on-success="actionFieldData"
            />

            <SelectedMain v-else-if="field.field == 'selectDataServer' && !field.hidden"
              :setterModel="model[field.nameid]" :itemAction="field" :on-success="actionFieldData" />

            <SearchDinamicField v-else-if="field.field == 'searchDataServer' && !field.hidden"
              :setterModel="model" :itemAction="field" :on-success="actionFieldData" />

            <SelectedGroup v-else-if="field.field == 'selectMultipleDataServer' && !field.hidden"
            :setterModel="model[field.nameid]"
            :itemAction="field"
            :on-success="actionFieldData"/>

            <PreviewPDF v-else-if="field.field == 'previewpdf' && !field.hidden"
            :setterModel="model"
            :itemAction="field" />

            <ViewMovementsField v-else-if="field.field == 'showMovsDataServer' && !field.hidden"
            :itemAction="field" />

          </v-col>
        </v-row>
      </v-form>
    </v-card-text>

    <v-card-actions>
      <v-layout justify-end>
        <v-btn
          class="mr-3"
          color="red darken-1"
          depressed
          outlined
          @click="onCancel">Cerrar</v-btn>
        <v-btn
          class="mr-3"
          color="success darken-1"
          depressed
          outlined
          @click="sendForm"> Guardar </v-btn>
      </v-layout>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { Validate } from '@/mixins/validateFormMixin'
import DatePicker from '@/components/fields/DatePicker'
import SelectedMain from '@/components/fields/SelectedDinamicField'
import SelectedGroup from '@/components/fields/SelectedGroupDinamicField'
import SearchDinamicField from '@/components/fields/SearchDinamicField'
import PreviewPDF from '@/components/fields/specific/PreviewPDF'
import ViewMovementsField from '@/components/fields/specific/ViewMovementsField'

export default {
  name: 'MainProcessForm',
  props: ['form', 'model', 'onSuccess', 'onCancel'],
  mixins: [Validate],
  data: () => ({
    selected: [],
    indice: '',
    textRules: [
      value => {
        if (value?.length >= 2) return true
        return 'El valor debe tener al menos 2 caracteres'
      },
      value => {
        if (/^[a-zA-Z0-9-.-\u00C0-\u00FF-\s]*$/.test(value)) return true
        return 'El valor no debe contener caracteres especiales'
      }
    ]
  }),
  components: {
    DatePicker,
    SelectedMain,
    SelectedGroup,
    SearchDinamicField,
    PreviewPDF,
    ViewMovementsField
  },
  methods: {
    ...mapActions(['postServiceActionRemoveRol']),
    RadioButtonEvent (item) {
      if (item.action) {
        this[item.action](item)
      }
    },
    ShowTipoPlaza (field) {
      const response = this.model[field.nameid]
      if (field.isHidden) {
        field.isHidden.map((item, i) => {
          this.form.status_finish.formulario.fields.map((form, index) => {
            if (item === form.nameid) {
              if (response === 'NO') {
                form.hidden = false
                this.model[form.nameid] = ''
                return
              }
              form.hidden = true
              this.model[form.nameid] = null
            }
          })
        })
      }
    },

    checkboxEvent (item) {},

    actionFieldData (obj) {
      this.model[obj.id] = (obj.data) ? obj.data : null
      console.log(obj)
      this.actionDinamicData(obj)
    },
    actionDinamicData (obj) {
      console.log(obj)
      if (obj.action) {
        this[obj.action](obj)
      }
    },
    removeRole (obj) {
    },

    ShowFieldDinamic (field) {
      const response = this.model[field.nameid]
      if (field.isHidden) {
        field.isHidden.map((item, i) => {
          this.form.map((form, index) => {
            if (item === form.nameid) {
              if (form.nameid === 'movimientos') {
                setTimeout(() => { form.hidden = true }, 200)
                form.url = ''
                if (response) {
                  form.url = `${form.default}?folio=${this.model.folio}&mov=${response}`
                  setTimeout(() => { form.hidden = false }, 200)
                } else {
                  setTimeout(() => { form.hidden = true }, 200)
                }
                console.log('url', form.url)
                this.model[form.nameid] = null
                return
              }
              form.hidden = true
              this.model[form.nameid] = null
            }
          })
        })
      }
    },

    findBillEmployee ({ id, data, field }) {
      if (field.isHidden) {
        field.isHidden.map((item, i) => {
          this.form.map((form, index) => {
            if (item === form.nameid) {
              if (form.hidden) {
                form.params[id] = data
                form.hidden = false
              }
            }
          })
        })
      }
    },
    sendForm () {
      if (this.$refs.form.validate()) {
        const item = this.validateComponents()
        if (item !== null) {
          this.$swal({
            type: 'error',
            icon: 'error',
            title: 'Advertencia!',
            text: ' Campo ' + item.name + ' no debe ser vacio!!'
          })
          return
        }
        this.onSuccess(this.model)
      }
    },
    validateComponents () {
      let itemSelected = null
      this.form.map(item => {
        if (item.rules) {
          if (!item.hidden) {
            if (!this.model[item.nameid]) {
              itemSelected = item
            } else if (this.model[item.nameid] === null) {
              itemSelected = item
            }
          }
        }
      })
      return itemSelected
    }
  },
  computed: {
    ...mapGetters([])
  },
  watch: {
  }
}
</script>

<style>
.box-color-red {
  border: 1px red solid;
}
div .checkbook-horizontal {
  display: inline-block;
}
</style>

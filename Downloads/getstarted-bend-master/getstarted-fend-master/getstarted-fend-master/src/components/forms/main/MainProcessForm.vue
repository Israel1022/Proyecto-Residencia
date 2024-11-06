<template>
  <v-card width="100%" tile elevation="0">
    <v-card-text class="py-1">
    <v-form ref="form">
      <v-row dense>
        <v-col v-for="(field, i) in form.status_finish.formulario.fields" :key="i"
              :cols="field.cols" :offset="field.offset" :hidden="field.hidden">
          <v-text-field v-if="field.field == 'text' && !field.hidden"
            v-model="model[field.nameid]"
            :label="field.name"
            :type="field.type"
            :rules="(field.rules)? fieldRequired : []"
            outlined
            dense>
          </v-text-field>

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

          <v-textarea v-else-if="field.field == 'textArea' && !field.hidden"
            v-model="model[field.nameid]"
            :label="field.name"
            :name="field.dimens"
            :rules="(field.rules)? fieldRequired : []"
            outlined
            dense>
          </v-textarea>

          <v-select v-else-if="field.field == 'select' && !field.hidden"
            v-model="model[field.nameid]"
            :label="field.name"
            :items="field.items"
            :rules="(field.rules)? fieldRequired : []"
            outlined
            dense>
          </v-select>

          <div v-else-if="field.field == 'radioButton' && !field.hidden">
            {{ isUpdate(field)}}
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
          <div v-else-if="field.field == 'switch' && !field.hidden">
            <v-switch
              flat
              dense
              v-model="model[field.nameid]"
              :label="field.name"
              :rules="(field.rules) ? fieldRequired : []"
            >
            </v-switch>
          </div>

          <div v-else-if="field.field == 'checkbox' && !field.hidden">
            <p v-if="field.name" class="ma-0 pa-0 text--black font-weight-bold font-weight-black subtitle-2">{{field.name}}</p>
              <v-checkbox v-for="value in field.items" :key="value" :class="(field.row)? 'mr-3 pa-0 checkbook-horizontal' : 'ma-0 pa-0'"
                v-model="model[field.nameid]"
                :label="(value.name) ? value.name : value"
                :value="(value.name) ? value.value : value"
                :rules="(field.rules) ? fieldRequired : []"
                >
              </v-checkbox>
          </div>

          <SelectedMain v-else-if="field.field == 'selectDataServer' && !field.hidden"
            :setterModel="model[field.nameid]" :itemAction="field" :on-success="actionFieldData" />
          <SelectedGroup v-else-if="field.field == 'selectGroupDataServer' && !field.hidden"
            :setterModel="model[field.nameid]" :itemAction="field" :on-success="actionFieldData" />
          <DropMenuTreeView v-else-if="field.field == 'dropMenuTreeviewDataServer' && !field.hidden"
            :setterModel="model[field.nameid]" :itemAction="field" :on-success="actionFieldData" />
          <SearchDinamicField v-else-if="field.field == 'searchDataServer' && !field.hidden"
            :setterModel="model" :itemAction="field" :on-success="actionFieldData" />

            <DatePicker v-else-if="field.field == 'textDatePicker' && !field.hidden"
            :setterModel="model[field.nameid]"
            :itemAction="field"
            :on-success="actionFieldData"
          />
          <SearchInsumosTableField v-else-if="field.field == 'SearchTableDataServer'&& !field.hidden" :item-action="field" :on-success="actionFieldData"/>
          <v-divider v-else-if="field.field == 'divider' && !field.hidden" class="dividerMain">
          </v-divider>
        </v-col>
      </v-row>
    </v-form>
  </v-card-text>
  <v-card-actions>
    <v-layout justify-end>
      <v-btn class="mr-3" color="red darken-1" depressed outlined @click="onCancel">Cerrar</v-btn>
      <v-btn class="mr-3" color="success darken-1" depressed outlined @click="sendForm">{{form.status_finish.nombre}}</v-btn>
    </v-layout>
  </v-card-actions>
</v-card>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { Validate } from '@/mixins/validateFormMixin'
import SelectedMain from '@/components/fields/SelectedDinamicField'
import SelectedGroup from '@/components/fields/SelectedGroupDinamicField'
import DropMenuTreeView from '@/components/fields/DropMenuTreeView'
import SearchDinamicField from '@/components/fields/SearchDinamicField'
import DatePicker from '@/components/fields/DatePicker'
import SearchInsumosTableField from '@/components/fields/specific/SearchInsumosTableField'
export default {
  name: 'MainProcessForm',
  props: ['form', 'modelmain', 'update', 'onSuccess', 'onCancel'],
  mixins: [Validate],
  data: () => ({
    selected: [],
    model: {}
  }),
  components: {
    SelectedMain,
    SelectedGroup,
    DropMenuTreeView,
    SearchDinamicField,
    DatePicker,
    SearchInsumosTableField
  },
  created () {
    this.model = this.modelmain
  },
  methods: {
    ...mapActions([]),
    isUpdate (field) {
      if (this.update) {
        this.RadioButtonEvent(field)
      }
    },
    RadioButtonEvent (item) {
      if (item.action) {
        this[item.action](item)
      }
    },
    ShowFieldDinamic (field) {
      const response = this.model[field.nameid]
      if (field.isHidden) {
        field.isHidden.map((item, i) => {
          this.form.status_finish.formulario.fields.map((form, index) => {
            if (item === form.nameid) {
              if (response === 'Programado') {
                form.hidden = false
                // this.model[form.nameid] = ''
                return
              } else if (response === 'No Programado') {
                form.hidden = true
                if (!this.update) this.model[form.nameid] = ''

                return
              } else if (response && field.nameid === 'equipo_inventario') {
                form.hidden = (form.nameid !== 'inventario_id')
                // this.model[form.nameid] = (form.nameid === 'registrar_equipo') ? false : null
                return
              } else if (!response && field.nameid === 'equipo_inventario') {
                form.hidden = (form.nameid === 'inventario_id')
                if (!this.update) this.model[form.nameid] = (form.nameid === 'registrar_equipo') ? true : null
                return
              } else if (response && field.nameid === 'interno') {
                form.hidden = (form.nameid !== 'solicitante_id')
                // if (!this.update) this.model[form.nameid] = (form.nameid === 'registrar_solicitante') ? false : null
                return
              } else if (!response && field.nameid === 'interno') {
                form.hidden = (form.nameid === 'solicitante_id')
                if (!this.update) this.model[form.nameid] = (form.nameid === 'registrar_solicitante') ? true : null
                return
              } else if (response && field.nameid === 'buscar_persona') {
                form.hidden = (form.nameid !== 'empleado_recibe_id')
                return
              } else if (!response && field.nameid === 'buscar_persona') {
                form.hidden = (form.nameid === 'empleado_recibe_id')
                if (!this.update) this.model[form.nameid] = null
                return
              } else if (response) {
                form.hidden = false
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

    ShowFieldDinamicComponent ({ data, field, id, objetModel }) {
      const response = data
      if (field.isHidden) {
        field.isHidden.map((item, i) => {
          this.form.status_finish.formulario.fields.map((form, index) => {
            if (item === form.nameid) {
              if (form.nameid === 'direccion_id') {
                form.url = ''
                if (response) {
                  form.url = `${form.default}?id=${response}`
                  setTimeout(() => { form.hidden = false }, 200)
                } else {
                  setTimeout(() => { form.hidden = true }, 200)
                }
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
    checkboxEvent (item) {},
    actionFieldData (obj) {
      this.model[obj.id] = (obj.data) ? obj.data : null
      this.actionDinamicData(obj)
    },
    actionDinamicData (obj) {
      if (obj.action) {
        this[obj.action](obj)
      }
    },
    findBillEmployee ({ id, data, field }) {
      if (field.isHidden) {
        field.isHidden.map((item, i) => {
          this.form.status_finish.formulario.fields.map((form, index) => {
            if (item === form.nameid) {
              if (form.hidden) {
                form.params[id] = data
                form.hidden = false
                return
              }
              form.params = {}
              form.hidden = true
              this.model[form.nameid] = null
            }
          })
        })
      }
    },
    validateComponents () {
      let itemSelected = null
      this.form.status_finish.formulario.fields.map((item) => {
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
    },
    sendForm () {
      if (this.$refs.form.validate()) {
        const item = this.validateComponents()
        if (item !== null) {
          this.$swal({
            type: 'error',
            icon: 'error',
            title: 'Advertencia!',
            text: ` Campo ${item.name} no debe ser vacio!!`
          })
          return
        }
        this.onSuccess({
          descripcion: this.model,
          action: this.form.accion,
          status: this.form.estatus_final_id,
          actual: this.form.estatus_actual_id
        })
      }
    }
  },
  computed: {
    ...mapGetters([]),
    showdata () {
      return this.model
    }
  },
  watch: {}
}
</script>

<style>
.box-color-red {
  border: 1px red solid;
}
div .checkbook-horizontal{
  display: inline-block;
}
.dividerMain{
  border: 2px #757575 solid;
  background-color:#757575
}
</style>

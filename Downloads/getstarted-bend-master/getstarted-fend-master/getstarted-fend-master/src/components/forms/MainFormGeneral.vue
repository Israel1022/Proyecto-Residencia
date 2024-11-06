<template>
  <v-card width="100%" variant="text">
    <v-card-text class="my-1">
      <v-form ref="form">
        <v-row dense>
          <v-col v-for="(field, i) in form" :key="i" :cols="field.cols" :offset="field.offset" :hidden="field.hidden">

            <InputTextMainField v-if="field.field == 'text' && !field.hidden"
              :config="field" :data="model[field.nameid]" :onSuccess="actionFieldData" />

              <TextAreaMainField v-if="field.field == 'textarea' && !field.hidden"
              :config="field" :data="model[field.nameid]" :onSuccess="actionFieldData" />

              <InputRadioButtonMainField v-if="field.field == 'radiobutton' && !field.hidden"
              :config="field" :data="model[field.nameid]" :onSuccess="actionFieldData" />

              <InputCheckBoxMainField v-if="field.field == 'checkbox' && !field.hidden"
              :config="field" :data="model[field.nameid]" :onSuccess="actionFieldData" />
              
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn v-if="get_MainFormDialog.body.showCancel"
        class="mx-3" color="red-darken-3" variant="outlined"
        :text="get_MainFormDialog.body.nameCancel">
      </v-btn>

      <v-btn v-if="get_MainFormDialog.body.showComfirm"
        class="mx-3" color="green-darken-3" variant="outlined"
        :text="get_MainFormDialog.body.nameComfirm" @click="sendForm">
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import InputTextMainField from '@/components/fields/inputs_controls/InputTextMain.Field.vue'
import TextAreaMainField from '@/components/fields/inputs_controls/TextAreaMain.Field.vue'
import InputRadioButtonMainField from '@/components/fields/inputs_controls/InputRadioButtonMain.Field.vue'
import InputCheckBoxMainField from '@/components/fields/inputs_controls/InputCheckBoxMain.Field.vue'
export default {
  props: ['form', 'model', 'onSuccess', 'onCancel'],
  mixins: [],
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
    InputTextMainField,
    TextAreaMainField,
    InputRadioButtonMainField,
    InputCheckBoxMainField
  },
  methods: {
    ...mapActions(['postServiceActionRemoveRol']),
    actionFieldData (obj) {
      this.model[obj.id] = (obj.data) ? obj.data : null
      this.actionDinamicData(obj)
    },
    actionDinamicData (obj) {
      if (obj.action) {
        this[obj.action](obj)
      }
    },
    /* *==========*==========*==========*==========*==========*==========*==========*==========* */

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
    ...mapGetters(['get_MainFormDialog'])
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

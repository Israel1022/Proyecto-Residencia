<template>
    <v-responsive>
        <v-radio-group
            v-model="selected"
            :color="(config.color) ? config.color : 'primary'"
            :column="config.column"
            :inline="config.inline"
            @change="RadioButtonEvent">
            <template v-slot:label>
                <label><strong>{{ config.name }}</strong></label>
            </template>
            <v-radio v-for="(value,index) in config.items" :key="index"
                :label="(value.name)? value.name : value"
                :value="(value.value)? value.value : value"/>
        </v-radio-group>
    </v-responsive>
</template>

<script>
export default {
  props: {
    config: Object,
    data: String,
    onSuccess: Function
  },
  data: () => ({
    selected: null
  }),
  methods: {
    RadioButtonEvent() {
        this.onSuccess({
            id: this.config.nameid,
            data: this.selected,
            objectname: null,
            objectMain: null,
            config: this.config
        })
    }
  },
  watch: {}
}
</script>
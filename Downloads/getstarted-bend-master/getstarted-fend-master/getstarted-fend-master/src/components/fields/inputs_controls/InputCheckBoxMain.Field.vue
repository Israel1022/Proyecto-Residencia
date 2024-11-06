<template>
    <v-responsive>
        <div class="pa-1">
            <label>
                <strong>{{ config.name }}</strong>
            </label>
        </div>
        <div :class="(config.inline) ? 'd-inline-flex': 'inline-block'">
        <v-checkbox v-for="(value,index) in config.items" :key="index"
            class="pa-1"
            v-model="selected"
            :color="(config.color)? config.color: 'primary'"
            :label="(value.name)? value.name : value"
            :value="(value.value)? value.value : value"
            density="compact"/>
        </div>
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
    selected: [],
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
  watch: {
    selected (v) {
        this.onSuccess({
            id: this.config.nameid,
            data: this.v,
            objectname: null,
            objectMain: null,
            config: this.config
        })
    }
  }
}
</script>
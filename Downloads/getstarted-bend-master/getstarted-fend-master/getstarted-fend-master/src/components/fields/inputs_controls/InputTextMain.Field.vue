<template>
    <v-responsive>
        <v-text-field
            v-model="texts"
            :label="config.name"
            :type="config.type"
            :maxlength="config.maxlength ? config.maxlength : false"
            :counter="config.counter ? config.counter : false"

            :append-icon="config.appendicon"
            :append-inner-icon="config.appendinnericon"
            :prepend-icon="config.prependicon"
            :prepend-inner-icon="config.prependinnericon"
            
            :prefix="config.prefix"
            :suffix="config.suffix"
            variant="solo-filled"
            density="compact"
            @click:clear="clearInput"
            clearable>
        </v-text-field>
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
    texts: null
  }),
  methods: {
    clearInput () {
        this.onSuccess({
            id: this.config.nameid,
            data: "",
            objectname: null,
            objectMain: null,
            config: this.config
        })
    },
    keyupText(e) {
        console.log(e)
        this.onSuccess({
            id: this.config.nameid,
            data: this.texts,
            objectname: null,
            objectMain: null,
            config: this.config
        })
    }
  },
  watch: {
    texts(t) {
        this.onSuccess({
            id: this.config.nameid,
            data: t,
            objectname: null,
            objectMain: null,
            config: this.config
        })
    }
  }
}
</script>
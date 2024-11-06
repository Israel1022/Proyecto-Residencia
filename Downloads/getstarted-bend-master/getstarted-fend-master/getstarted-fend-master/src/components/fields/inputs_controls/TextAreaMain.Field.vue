<template>
    <v-responsive>
        <v-textarea
        v-model="texts"
        :label="config.name"
        :bg-color=" (config.bgcolor) ? config.bgcolor: ''"
        :color="(config.color) ? config.color:'black'"
        :rows="(config.rows) ? config.rows : 4"

        :counter="config.counter ? config.counter : false"

        variant="solo-filled"
        density="compact"
        @click:clear="clearInput"
        clearable>
        </v-textarea>
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
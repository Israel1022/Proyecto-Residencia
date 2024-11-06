<template>
  <v-menu v-model="menu" :close-on-content-click="false" location="end">
    <template v-slot:activator="{ props }">
      <v-btn v-bind="props" class="text-body-2 ml-2 bg-blue-grey-lighten-1" icon="mdi-view-headline" variant="text" size="small">
      </v-btn>
    </template>
    <v-card min-width="300" max-height="400">
      <v-list nav density="compact">
        <span v-for="(option, index) in item" :key="index">
          <v-list-item v-if="!option.hidden && option.action" @click="ItemAction(option)" class="text-caption" variant="plain">
            <template v-slot:prepend>
              <v-icon :icon="option.icon" color="blue-grey-darken-3"></v-icon>
            </template>
            <v-list-item-title>
              <span class="font-weight-bold text-uppercase">{{option.title}}</span>
            </v-list-item-title>
          </v-list-item>

          <v-list-group v-else-if="option.options">
            <template v-slot:activator="{ props }">
              <v-list-item v-bind="props" class="text-caption" variant="plain">
                <template v-slot:prepend>
                    <v-icon :icon="option.icon" color="blue-grey-darken-3"></v-icon>
                </template>
                <v-list-item-title>
                  <span class="font-weight-bold text-uppercase">{{option.title}}</span>
                </v-list-item-title>
              </v-list-item>
            </template>

            <v-list-item v-for="(op, index) in option.options" :key="index"
              @click="ItemAction(op)" class="text-caption" variant="plain">
              <template v-slot:prepend>
                <v-icon :icon="op.icon" color="blue-grey-darken-3"></v-icon>
              </template>
              <v-list-item-title>
                <span class="font-weight-bold text-uppercase">{{op.title}}</span>
              </v-list-item-title>
            </v-list-item>
          </v-list-group>

        </span>
      </v-list>
    </v-card>
  </v-menu>
</template>
<script>
export default {
  props:['item', 'OnSuccess'],
  data: () => ({
    menu: false
  }),
  components:{ },
  mounted() {},
  methods: {
    ItemAction (action) {
      if (this.OnSuccess) this.OnSuccess(action)
    }
  }
}
</script>  
<style scoped>
</style>
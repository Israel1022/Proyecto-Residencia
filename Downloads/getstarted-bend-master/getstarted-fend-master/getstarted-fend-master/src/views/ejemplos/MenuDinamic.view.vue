<template>
<v-container>
    <v-row>
        <v-card class="mx-auto" width="300">
            <v-list>
                <div v-for="(item, i) in items" :key="i">
                    <v-list-item v-if="item.type == 'action'">
                        <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item>

                    <v-list-group v-if="item.type == 'subActions'" no-action prepend-icon="mdi-cog">
                    <template v-slot:activator>
                        <v-list-item-content>
                        <v-list-item-title>Configuraciones</v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item  v-for="(item, i) in item.items" :key="i" link>
                        <v-list-item-title>Config. Ejercicio</v-list-item-title>
                        <v-list-item-icon>
                        <v-icon>mdi-calendar-blank</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                    </v-list-group>
                </div>
            </v-list>
        </v-card>
    </v-row>
</v-container>
</template>

<script>
export default {
  data: () => ({
    items: [
      {
        title: 'Action 1',
        icon: 'mdi-home',
        action: 'action',
        type: 'subActions',
        items: [
          {
            title: 'SubAction 1',
            icon: 'mdi-cog',
            action: 'action',
            type: 'action'
          },
          {
            title: 'SubAction 2',
            icon: 'mdi-cog',
            action: 'action',
            type: 'action'
          },
          {
            title: 'SubAction 3',
            icon: 'mdi-cog',
            action: 'action',
            type: 'action'
          }
        ]
      },
      {
        title: 'Action 2',
        icon: 'mdi-home',
        action: 'action',
        type: 'action'
      },
      {
        title: 'Action 3',
        icon: 'mdi-home',
        action: 'action',
        type: 'action'
      },
      {
        title: 'Action 4',
        icon: 'mdi-home',
        action: 'action',
        type: 'action'
      },
      {
        title: 'Action 5',
        icon: 'mdi-home',
        action: 'action',
        type: 'subActions',
        items: [
          {
            title: 'SubAction 1',
            icon: 'mdi-cog',
            action: 'action',
            type: 'action'
          },
          {
            title: 'SubAction 2',
            icon: 'mdi-cog',
            action: 'action',
            type: 'action'
          }
        ]
      }
    ]
  }),
  mounted () {
  },
  methods: {
  },
  computed: {
  },
  components: {
  }
}
</script>

<style>

</style>

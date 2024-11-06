<template>
  <span v-for="(i, index) in menu" :key="index">
    <div>
      <ButtonTooltipMenu v-if="i.group" :item="i" :OnSuccess="ItemAction"/>
      <ButtonTooltip v-else :item="i" :OnSuccess="ItemAction"/>
    </div>
  </span>
</template>
<script>
import ButtonTooltip from "@/components/fields/buttons/ButtonTooltip.Field.vue"
import ButtonTooltipMenu from "@/components/fields/buttons/ButtonTooltipMenu.Field.vue"
export default {
  props: ['menu', 'OnSuccess'],
  data: () => ({}),
  components: { ButtonTooltip, ButtonTooltipMenu },
  methods: {
    ItemAction (action) {
      if (this.OnSuccess) this.OnSuccess(action)
    }
  },
}
</script>
<style scoped></style>
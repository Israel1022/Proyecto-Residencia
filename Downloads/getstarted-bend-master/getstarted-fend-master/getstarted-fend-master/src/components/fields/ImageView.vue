<template>
  <article class="layout column align-center">
    <img class="image" :src="imageSource" :alt="`${imageName} picture`" :width="width" >
  </article>
</template>

<script>
export default {
  name: 'imageView',
  props: {
    imageName: String,
    width: String
  },
  computed: {
    imageSource () {
      return require(`@/assets/images/${this.imageName}`)
    }
  }
}

</script>

<style>
</style>

<template>
  <v-container fluid>
    <UploadExcelComponent :on-success="handleSuccess" :before-upload="beforeUpload"/>
  {{ $data }}
  </v-container>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from 'vuex'
import UploadExcelComponent from '@/components/importador/file'
export default {

  name: 'dialogFaltas',
  props: {
    onSuccess: Function
  },
  data: () => ({
    tableHeader: [],
    tableData: []
  }),
  methods: {
    ...mapActions([]),
    beforeUpload (file) {
      const isLt1M = file.size / 1024 / 1024 < 2
      if (isLt1M) {
        return true
      }
      console.log({
        message: isLt1M + ' --Please do not upload files larger than 1m in size.',
        type: 'warning'
      })
      return false
    },
    handleSuccess ({ results, header }) {
      this.tableData = results
      this.tableHeader = header
    },
    ...mapMutations([])
  },
  components: {
    UploadExcelComponent
  },
  computed: {
    ...mapGetters([])
  }
}
</script>

<style>
</style>

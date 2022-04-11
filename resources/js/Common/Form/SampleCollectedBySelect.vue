
<template>
  <SelectInput
    v-model="iValue"
    :items="sampleCollectedBy || []"
    item-text="label"
    item-value="id"
    :errors="errors"
    :label="header"
  />
</template>

<script>
import SelectInput from '@/Common/Form/SelectInput'

export default {
  components: {
    SelectInput,
  },

  props: {
    value: {},
    errors: {String, Object},
    header: {
      type: String,
      default: window.lang.get('common.sample_collected_by'),
    },
    requestParams:  {
      Type: String,
      default: '',
    },
  },

  data() {
    return {
      lang: window.lang,
      sampleCollectedBy: [],
    }
  },

  computed: {
    iValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  watch: {
    requestParams() {
      this.getSampleCollectedBy()
    },
  },
  mounted() {
    if (!this.sampleCollectedBy.length) {
      this.getSampleCollectedBy()
    }
  },

  methods: {
    getSampleCollectedBy() {
      let _this = this
      if(_this.requestParams == '') return
      _this.$store.dispatch('common/getSampleCollectedBy', _this.requestParams)
        .then(employees => {
          _this.sampleCollectedBy = employees
        })
        .catch(() => {})
    },
  },
}
</script>

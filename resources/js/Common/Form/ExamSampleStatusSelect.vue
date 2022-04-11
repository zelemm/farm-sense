<template>
  <SelectInput
    v-model="iValue"
    :items="statuses || []"
    :errors="errors"
    :label="heading"
    :loading="loading"
    :readonly="readonly"
    :disabled="disabled"
    :placeholder="placeholder"
  />
</template>

<script>
export default {
  components: {
    SelectInput: () => import('@/Common/Form/SelectInput'),
  },

  props: {
    value: {},
    url: {
      Type: String,
      default: '',
    },
    heading: {
      type: String,
      default: window.lang.get('form.heading.exam_sample_status')
    },
    requestParams:  {
      Type: String,
      default: '',
    },
    placeholder: String,
    errors: {String, Object},
    readonly: {String, Boolean},
    disabled: {Boolean, String},
  },
  data() {
    return {
      lang: window.lang,
      loading: false,
    }
  },
  
  computed: {
    statuses() {
      return this.$store.getters['common/examSampleStatuses']
    },

    iValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      }
    },
  },

  mounted() {
    if (!this.statuses) {
      this.getExamSampleStatuses()
    }
  },

  methods: {
    getExamSampleStatuses() {
      this.loading = true
      this.$store.dispatch('common/getExamSampleStatuses')
        .then(() => {
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>

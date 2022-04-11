<template>
  <SelectInput
    v-model="iValue"
    :items="equationClass || []"
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
      default: window.lang.get('form.heading.equation_class')
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
    equationClass() {
      return this.$store.getters['serviceWorkflow/equationClass']
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
    if (!this.equationClass) {
      this.getEquationClass()
    }
  },

  methods: {
    getEquationClass() {
      this.loading = true
      this.$store.dispatch('serviceWorkflow/getEquationClass')
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

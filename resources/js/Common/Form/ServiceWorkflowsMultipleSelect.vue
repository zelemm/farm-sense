<template>
  <MultipleSelectInput
    v-model="iValue"
    :items="serviceWorkflows || []"
    :label="label"
    :loading="loadingWorkflows"
    :errors="errors"
  />
</template>

<script>
export default {
  components: {
    MultipleSelectInput: () => import('@/Common/Form/MultipleSelectInput'),
  },

  props: {
    value: {},
    errors: {String, Object},
    label: {
      type: String,
      default: lang.get('form.heading.service_workflows')
    },
  },

  data() {
    return {
      lang: window.lang,
      items: [],
      loadingWorkflows: false,
    }
  },

  computed: {
    serviceWorkflows() {
      return this.$store.getters['common/serviceWorkflows']
    },

    iValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },

  mounted() {
    if (!this.serviceWorkflows) {
      this.getServiceWorkflows()
    }
  },

  methods: {
    getServiceWorkflows() {
      let _this = this
      _this.loadingWorkflows = true
      _this.$store.dispatch('common/getServiceWorkflows')
        .then(() => {
          _this.loadingWorkflows = false
        })
        .catch(() => {
          _this.loadingWorkflows = false
        })
    }
  }
}
</script>

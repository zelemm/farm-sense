<template>
  <MultipleSelectInput
    v-model="iValue"
    :items="associatedLabs || []"
    :label="label"
    :loading="loadingAssociatedLabs"
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
      default: lang.get('form.heading.associated_labs')
    },
  },

  data() {
    return {
      lang: window.lang,
      loadingAssociatedLabs: false,
    }
  },

  computed: {
    associatedLabs() {
      return this.$store.getters['common/associatedLabs']
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
    if (!this.associatedLabs) {
      this.getAssociatedLabs()
    }
  },

  methods: {
    getAssociatedLabs() {
      this.loadingAssociatedLabs = true
      this.$store.dispatch('common/getAssociatedLabs')
        .then(res => {
          this.loadingAssociatedLabs = false
        })
        .catch(error => {
          this.loadingAssociatedLabs = false
        })
    }
  }
}
</script>

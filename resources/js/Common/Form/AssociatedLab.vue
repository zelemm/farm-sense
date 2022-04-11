<template>
  <SelectInput
    v-model="iValue"
    :items="associatedLabs || []"
    item-text="name"
    item-value="id"
    :errors="errors"
    :label="lang.get('form.associated_lab')"
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

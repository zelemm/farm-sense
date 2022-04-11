<template>
  <SelectInput
    v-model="iValue"
    :items="locations || []"
    item-text="name"
    item-value="id"
    :errors="errors"
    :label="heading"
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
    heading: {
      type: String,
      default: window.lang.get('form.heading.location')
    },
  },

  data() {
    return {
      lang: window.lang,
    }
  },

  computed: {
    locations() {
      return this.$store.getters['common/locationsList']
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
    if (!this.locations) {
      this.getLocations()
    }
  },

  methods: {
    getLocations() {
      this.$store.dispatch('common/getLocationsList')
    }
  }
}
</script>

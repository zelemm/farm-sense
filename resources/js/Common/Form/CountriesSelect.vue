<template>
  <SelectInput
    v-model="iValue"
    :items="countries || []"
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
    url: {
      Type: String,
      default: '',
    },
    heading: {
      type: String,
      default: window.lang.get('form.country')
    },
    requestParams:  {
      Type: String,
      default: '',
    },
    errors: {
      Type: String,
      default: null,
    },
  },
  data() {
    return {
      lang: window.lang,
    }
  },
  
  computed: {
    countries() {
      return this.$store.getters['location/countries']
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
    if (!this.countries) {
      this.getCountries()
    }
  },

  methods: {
    getCountries() {
      this.$store.dispatch('location/getCountries')
    },
  },
}
</script>

<template>
  <SelectInput
    v-model="iValue"
    :items="items"
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
      default: window.lang.get('form.heading.type')
    },
  },

  data() {
    return {
      lang: window.lang,
      items: ['Mobile Storage', 'Refrigerator Storage -20', 'Freezer Storage -80', 'Thermocyclers', 'Sample Processing'],
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
}
</script>

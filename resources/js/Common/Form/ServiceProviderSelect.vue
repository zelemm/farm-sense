
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
      default: window.lang.get('form.heading.service_provider')
    },
  },

  data() {
    return {
      lang: window.lang,
      items: [
        'CFX', 'Axygen', 'LGC Biotecnologia', 'Sigma Aldrich', 'Casalab', 'FORP'
      ],
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

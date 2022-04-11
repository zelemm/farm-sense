<template>
  <TextInput
    v-model="iValue"
    :errors="errors"
    :label="heading"
  />
</template>

<script>
import TextInput from '@/Common/Form/TextInput'

export default {
  components: {
    TextInput,
  },

  props: {
    value: {},
    errors: {String, Object},
    heading: {
      type: String,
      default: window.lang.get('form.heading.model'),
    },
  },

  data() {
    return {
      lang: window.lang,

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

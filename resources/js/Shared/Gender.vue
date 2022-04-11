<template>
  <SelectInput
    v-model="iValue"
    :items="items"
    item-text="name"
    item-value="value"
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
    heading: {
      type: String,
      default: window.lang.get('form.gender'),
    },
    errors:  {
      Type: String,
      default: null,
    },
  },

  data() {
    return {
      lang: lang,
      items: [
        {
          value: '',
          name: window.lang.get('form.gender'),
        },
        {
          value: 'Male',
          name: window.lang.get('common.male'),
        },
        {
          value: 'Female',
          name: window.lang.get('common.female'),
        },
        {
          value: 'Other',
          name: window.lang.get('common.other'),
        },
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

  methods: {

  },
}
</script>

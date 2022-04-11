<template>
  <SelectInput
    v-model="iValue"
    :items="items"
    :errors="errors"
    :label="heading"
  />
</template>

<script>
export default {
  components: {
    SelectInput: () => import('@/Common/Form/SelectInput'),
  },

  props: {
    value: {},
    errors: {String, Object},
    items: {
      type: Array,
      default: () => ['Active', 'Inactive'],
    },
    heading: {
      type: String,
      default: window.lang.get('form.heading.status')
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

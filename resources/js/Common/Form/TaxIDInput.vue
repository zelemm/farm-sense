<template>
  <div class="w-full">
    <TextInput
      v-model="iValue"
      :label="!hideLabel ? label : ''"
      :type="type"
      :step="step"
      :readonly="readonly"
      :disabled="disabled"
      :placeholder="placeholder"
      :errors="errors"
    />
  </div>
</template>

<script>
export default {
  components: {
    TextInput: () => import('@/Common/Form/TextInput'),
  },

  props: {
    value: {},
    errors: {String, Object},
    step: {String, Number},
    label: {
      type: String,
      default: window.lang.get('form.heading.tax_id'),
    },
    hideLabel: {
      type: Boolean,
      default: false,
    },
    placeholder: String,
    type: {
      type: String,
      default: 'text',
    },
    readonly: {Boolean, String},
    disabled: {Boolean, String},
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


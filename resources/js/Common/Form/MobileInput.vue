<template>
  <div class="w-full">
    <TextInput
      v-model="iValue"
      v-mask="['+## ## #####-####']"
      :label="label"
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
import TextInput from '@/Common/Form/TextInput'

export default {
  name:'MobileInput',
  components: {
    TextInput,
  },

  props: {
    value: {},
    errors: {String, Object},
    step: {String, Number},
    label: {
      type: String,
      default: window.lang.get('report.mobile'),
    },
    placeholder: String,
    type: {
      type: String,
      default: 'tel',
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


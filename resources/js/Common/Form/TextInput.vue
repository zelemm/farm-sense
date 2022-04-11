<template>
  <div class="form-group" :class="{ 'has-prepend': prepend, 'has-error': error }">
    <label v-if="label">{{ label }}
      <v-icon class="ml-1" v-if="loading">mdi mdi-spin mdi-18px mdi-loading</v-icon>
    </label>

    <span
      v-if="prepend"
      class="input-prepend"
    >
      {{ prepend }}
    </span>

    <input
      v-model="iValue"
      :type="type"
      :step="step"
      class="form-control"
      :class="{ 'is-invalid': error }"
      :placeholder="placeholder"
      :readonly="readonly"
      :disabled="disabled"
      :autocomplete="autocomplete"
      :autofocus="autofocus"
      :min="min"
      :max="max"
      @keyup.enter="keyup('enter')"
    >

    <v-icon v-if="clearable && iValue" @click="clear" class="input-clear-icon">mdi mdi-24px mdi-close</v-icon>

    <small v-if="hint" class="form-text text-muted">{{ hint }}</small>

    <div v-if="error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    errors: {String, Object},
    step: {String, Number},
    label: String,
    placeholder: String,
    type: {
      type: String,
      default: 'text',
    },
    prepend: String,
    hint: String,
    loading: {Boolean, String},
    readonly: {Boolean, String},
    disabled: {Boolean, String},
    clearable: {
      type: Boolean,
      default: false,
    },
    autocomplete: {Boolean, Text},
    autofocus: {Boolean},
    min: {String, Number},
    max: {String, Number},
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

    error() {
      if (this.errors && this.errors instanceof Array) {
        return this.errors[0]
      }

      return this.errors
    }
  },

  methods: {
    clear() {
      this.iValue = null;
    },

    keyup(action) {
      this.$emit(action)
    },
  },
}
</script>

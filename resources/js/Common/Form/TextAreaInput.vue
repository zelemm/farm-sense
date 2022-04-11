<template>
  <div class="form-group">
    <label v-if="label">{{ label }}
      <v-icon class="ml-1" v-if="loading">mdi mdi-spin mdi-18px mdi-loading</v-icon>
    </label>

    <v-textarea
      v-model="iValue"
      :type="type"
      :rows="rows"
      outlined dense auto-grow clearable
      :placeholder="placeholder"
      :readonly="readonly"
      :disabled="disabled"
    />

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
    label: {
      type: String,
      default: window.lang.get('form.remarks'),
    },
    placeholder: String,
    type: {
      type: String,
      default: 'text',
    },
    rows: {
      type: [String, Number],
      default: 5,
    },
    hint: String,
    loading: {Boolean, String},
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

    error() {
      if (this.errors && this.errors instanceof Array) {
        return this.errors[0]
      }

      return this.errors
    }
  },
}
</script>

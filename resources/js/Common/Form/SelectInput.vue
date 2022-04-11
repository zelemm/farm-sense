<template>
  <div class="form-group">
    <label v-if="label" for="">{{ label }}
      <v-icon class="ml-1" v-if="loading">mdi mdi-spin mdi-18px mdi-loading</v-icon>
    </label>

    <v-autocomplete
      v-model="iValue"
      :class="{ 'is-invalid': error ? true : false }"
      :items="items || []"
      :item-text="itemText"
      :item-value="itemValue"
      :placeholder="placeholder"
      :persistent-placeholder="true"
      :readonly="readonly"
      :disabled="disabled"
      :hide-details="hideDetails"
      :error="error ? true : false"
      :clearable="clearable"
      :no-data-text="lang.get('common.data_table.no_data')"
      outlined
      dense
    />

    <div v-if="!hideMessages && error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    errors: {String, Object},
    label: String,
    items: Array,
    itemText: {
      type: String,
      default: 'text',
    },
    loading: {Boolean, String},
    itemValue: {
      type: String,
      default: 'value',
    },
    placeholder: String,
    readonly: {Boolean, String},
    disabled: {Boolean, String},
    hideDetails: {
      type: Boolean,
      default: false,
    },
    hideMessages: Boolean,
    clearable: Boolean,
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

    error() {
      if (this.errors && this.errors instanceof Array) {
        return this.errors[0]
      }

      return this.errors
    }
  },
}
</script>


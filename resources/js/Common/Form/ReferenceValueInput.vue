<template>
  <div class="form-group has-prepend" :class="{ 'has-error': error }">
    <label v-if="label">{{ label }}
      <v-icon class="ml-1" v-if="loading">mdi mdi-spin mdi-18px mdi-loading</v-icon>
    </label>

    <v-menu offset-y>
      <template v-slot:activator="{ on, attrs }">
        <span
          class="input-prepend text-capitalize"
          v-bind="attrs"
          v-on="on"
        >
          {{ selected }}
        </span>
      </template>

      <v-list>
        <v-list-item link @click="changeSelect('pt')">
          <v-list-item-title>Pt</v-list-item-title>
        </v-list-item>
        <v-list-item link @click="changeSelect('en')">
          <v-list-item-title>En</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>

    <input
      v-model="form.reference_value[selected]"
      class="form-control"
      :class="{ 'is-invalid': errors.reference_value }"
      :placeholder="placeholder"
      :readonly="readonly"
      :disabled="disabled"
    >

    <v-icon v-if="clearable && form.reference_value[selected]" @click="clear" class="input-clear-icon">mdi mdi-24px mdi-close</v-icon>

    <small v-if="hint" class="form-text text-muted">{{ hint }}</small>

    <div v-if="error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    form: Object,
    errors: Object,
    label: {
      type: String,
      default: window.lang.get('form.heading.reference_value'),
    },
    placeholder: String,
    hint: String,
    loading: {Boolean, String},
    readonly: {Boolean, String},
    disabled: {Boolean, String},
    clearable: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      selected: 'pt',
    }
  },

  computed: {
    error() {
      if (this.errors && this.errors.reference_value && this.errors.reference_value instanceof Array) {
        return this.errors.reference_value[0]
      }

      return this.errors.reference_value
    },
  },

  methods: {
    clear() {
      this.form.reference_value[this.selected] = null
    },

    changeSelect(lang) {
      this.selected = lang
    },
  },
}
</script>

<template>
  <v-menu ref="menu"
    v-model="date"
    :close-on-content-click="false"
    transition="scale-transition"
    min-width="auto"
    offset-y
    :disabled="disabled"
  >
    <template v-slot:activator="{ on, attrs }">
      <div class="form-group" :class="{ 'has-error': error, 'mb-0': noMarginBot, }">
        <label v-if="label" for="">{{ label }}</label>

        <v-text-field
          v-model="iValue"
          v-on="on"
          v-bind="attrs"
          @click:clear="date = null"
          class="c-form-control"
          :class="{ 'is-invalid': error }"
          append-icon="mdi-calendar"
          clearable outlined dense readonly
          :disabled="disabled"
          :error="error ? true : false"
          hide-details
        />

        <div v-if="error" class="invalid-feedback">
          {{ error }}
        </div>
      </div>
    </template>
    <v-date-picker
      v-model="iValue"
      @input="date = false"
      :max="maxDate"
      :min="minDate"
      :locale="lang.locale"
      :disabled="disabled"
    />
  </v-menu>
</template>

<script>
import moment from 'moment'

export default {
  props: {
    value: [String, Date],
    label: String,
    errors: {String, Object},
    minDate: [String, Date],
    maxDate: [String, Date],
    disabled: {
      type: Boolean,
      default: false,
    },
    noMarginBot: Boolean,
  },

  data() {
    return {
      lang: window.lang,
      date: false,
    }
  },

  methods: {
    formatDate(value) {
      if (typeof value === 'string' || value instanceof String) {
        let date = moment(value, 'DD/MM/YYYY')

        if (!date.isValid()) {
          date = moment(value, 'YYYY-MM-DD')
        }

        if (!date.isValid()) {
          date = moment(value, 'MM/DD/YYYY')
        }

        value = date.isValid() ? date.format('DD-MM-YYYY') : null
      }

      return value
    },
  },

  computed: {
    iValue: {
      get() {
        let value = this.value
        if (typeof value === 'string' || value instanceof String) {
          let date = moment(value, 'DD/MM/YYYY')

          if (!date.isValid()) {
            date = moment(value, 'YYYY-MM-DD')
          }

          if (!date.isValid()) {
            date = moment(value, 'MM/DD/YYYY')
          }

          value = date.isValid() ? date.format('YYYY-MM-DD') : null
        }

        return value
      },
      set(newValue) {
        let value = newValue

        if (value instanceof Date) {
          let dateTime = moment(value)
          var utcDateTime = moment(dateTime).utc().add(dateTime.utcOffset(), 'm').format('YYYY-MM-DD')

          value = utcDateTime
        }

        this.$emit('input', value)
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

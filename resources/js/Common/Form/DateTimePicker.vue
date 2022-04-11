
<template>
  <div class="form-group">
    <label for="">{{ heading }}</label>
    <v-datetime-picker
      v-model="iValue"
      dateFormat="dd/MM/yyyy"
      class="datetime-picker"
      :text-field-props="{ outlined: true, dense: true }"
      :date-picker-props="{
        min: minDate,
        max: maxDate,
        locale: lang.locale,
      }"
    >
      <template v-slot:dateIcon>
        <i class="mdi mdi-calendar" />
      </template>
      <template v-slot:timeIcon>
        <i class="mdi mdi-clock" />
      </template>
    </v-datetime-picker>

    <div v-if="error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
import moment from 'moment-timezone'

export default {
  props: {
    value: [String, Date],
    heading: String,
    minDate: [String, Date],
    maxDate: [String, Date],
    errors: {String, Object},
  },

  data() {
    return {
      lang: window.lang,
    }
  },

  computed: {
    iValue: {
      get() {
        let value = this.value
        if (typeof value === 'string' || value instanceof String) {
          let date = moment(value, 'DD-MM-YYYY HH:mm')

          if (!date.isValid()) {
            date = moment(value, 'YYYY-MM-DD HH:mm')
          }

          value = date.isValid() ? date.format('DD/MM/YYYY HH:mm') : null
        }

        return value
      },
      set(newValue) {
        let value = newValue

        if (value instanceof Date) {
          let dateTime = moment(value)
          let utcDateTime = moment(dateTime).utc().add(dateTime.utcOffset(), 'm').format('DD/MM/YYYY HH:mm')

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

  mounted() {
    // remove timezone
    moment.tz.setDefault()
  },
}
</script>

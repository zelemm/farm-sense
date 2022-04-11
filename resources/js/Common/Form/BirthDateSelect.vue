
<template>
  <div class="form-group">
    <label for="">{{ heading }}</label>

    <v-row>
      <v-col cols="4">
        <SelectInput
          class="normal-form-group"
          v-model="birthDateData.day"
          :placeholder="lang.get('form.date_lang.day')"
          :items="days"
          :readonly="readonly"
          :disabled="disabled"
          :hide-messages="true"
          :errors="error"
        />
      </v-col>

      <v-col cols="4">
        <SelectInput
          class="normal-form-group"
          v-model="birthDateData.month"
          :placeholder="lang.get('form.date_lang.month')"
          :items="months"
          item-text="text"
          item-value="value"
          :readonly="readonly"
          :disabled="disabled"
          :hide-messages="true"
          :errors="error"
        />
      </v-col>

      <v-col cols="4">
        <SelectInput
          class="normal-form-group"
          :placeholder="lang.get('form.date_lang.year')"
          v-model="birthDateData.year"
          :items="years"
          :readonly="readonly"
          :disabled="disabled"
          :hide-messages="true"
          :errors="error"
        />
      </v-col>
    </v-row>

    <div v-if="error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  components: {
    SelectInput: () => import('@/Common/Form/SelectInput'),
  },

  props: {
    value: [String, Date],
    heading: {
      type: String,
      default: window.lang.get('form.birth_date')
    },
    errors: {String, Object},
    readonly: {Boolean, String},
    disabled: {Boolean, String},
  },

  data() {
    return {
      lang: window.lang,
      days: Array.from({length:31}, (v,k) => k + 1),
      birthDateData: {
        day: '',
        month: '',
        year: '',
      },
    }
  },

  computed: {
    years() {
      let now = new Date(),
          year = now.getFullYear();

      return Array.from({length: 130}, (v,k) => year - k)
    },

    months() {
      let months = [],
          monthLabels = [
            lang.get('form.month_label.jan'),
            lang.get('form.month_label.feb'),
            lang.get('form.month_label.mar'),
            lang.get('form.month_label.apr'),
            lang.get('form.month_label.may'),
            lang.get('form.month_label.jun'),
            lang.get('form.month_label.jul'),
            lang.get('form.month_label.aug'),
            lang.get('form.month_label.sep'),
            lang.get('form.month_label.oct'),
            lang.get('form.month_label.nov'),
            lang.get('form.month_label.dec'),
          ];

      months = Array.from({length:12},(v,k) => {
        return {
          text: monthLabels[k],
          value: k + 1,
        }
      })

      return months;
    },

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
    },
  },

  watch: {
    birthDateData: {
      handler: function (newVal, oldVal) {
        let day = newVal.day || '',
            month = newVal.month || '',
            year = newVal.year || '';

        if (!day || !month || !year) {
          this.iValue = null
        } else {
          this.iValue = `${day.toString().padStart(2, '0')}/${month.toString().padStart(2, '0')}/${year}`
        }
      },
      deep: true,
    },

    value(val) {
      let selectedDate = this.value ? this.value.split('/') : []

      this.birthDateData = {
        month: selectedDate[1] ? parseInt(selectedDate[1]) : '',
        day: selectedDate[0] ? parseInt(selectedDate[0]) : '',
        year: selectedDate[2] ? parseInt(selectedDate[2]) : '',
      }
    }
  },

  created() {
    let selectedDate = this.value ? this.value.split('/') : []

    this.birthDateData = {
      month: selectedDate[1] ? parseInt(selectedDate[1]) : '',
      day: selectedDate[0] ? parseInt(selectedDate[0]) : '',
      year: selectedDate[2] ? parseInt(selectedDate[2]) : '',
    }
  }
}
</script>

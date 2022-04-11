<template>
  <div class="form-group">
    <label>{{ label }}</label>
    <v-file-input
      class="file-input"
      :rules="rules"
      v-model="iValue"
      :error-messages="error"
      color="deep-purple accent-4"
      accept="image/*"
      outlined multiple dense show-size clearable counter
    >
      <template v-slot:selection="{ text }">
        <v-chip color="deep-purple accent-4" dark label small>
          {{ text }}
        </v-chip>
      </template>
    </v-file-input>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    errors: {String, Object},
    label: {
      type: String,
      default: window.lang.get('form.heading.images')
    },
  },

  data() {
    return {
      loading: false,
      lang: window.lang,
      rules: [
        value => !value || value.length <= 5 || window.lang.get('form.farm_lang.images_rules'),
      ],
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

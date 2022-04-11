<template>
  <div class="form-group" :class="{ 'has-error': error }">
    <label>{{ label }}</label>
    <v-file-input
      class="file-input"
      :class="{ 'is-invalid': error }"
      v-model="iValue"
      :error="error ? true : false"
      :error-messages="error"
      color="deep-purple accent-4" 
      :accept="accept"
      :multiple="multiple ? true : false"
      :counter="counter ? true : false"
      outlined dense show-size clearable
      :disabled="disabled"
      @change="fileChanged"
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
    multiple: Boolean,
    counter: Boolean,
    label: {
      type: String,
      default: window.lang.get('form.heading.images')
    },
    disabled: Boolean,
    accept: {
      type: String,
      default: 'image/*, .pdf',
    },
  },

  data() {
    return {
      loading: false,
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
    },
  },

  methods: {
    fileChanged() {
      this.$emit('fileChanged')
    },
  }
}
</script>

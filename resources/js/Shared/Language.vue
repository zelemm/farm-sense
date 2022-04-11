<template>
  <div class="form-group">
    <label for="">{{ heading }}</label>
    <v-select
      v-model="iValue"
      class="c-form-control"
      :items="items"
      item-text="text"
      item-value="value"
      :error-messages="errors"
      @change="changeLang"
      outlined dense
    />
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    heading: {
      type: String,
      default: window.lang.get('form.lang'),
    },
    errors:  {
      Type:String,
      default:'',
    },
  },
  data() {
    return {
      lang: lang,
      loading: false,
      items: [
        { text: 'English', value: 'en' },
        { text: 'Portugese', value: 'pt' },
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
      }
    }
  },

  methods: {
    changeLang() {
      this.$emit('changeLang')
    }
  }
}
</script>


<template>
  <SelectInput
    v-model="iValue"
    :items="items"
    :errors="errors"
    :label="heading"
  />
</template>

<script>
import axios from 'axios';

export default {
  components: {
    SelectInput: () => import('@/Common/Form/SelectInput'),
  },

  props: {
    value: {},
    errors: {String, Object},
    heading: {
      type: String,
      default: window.lang.get('form.heading.location')
    },
  },

  data() {
    return {
      lang: window.lang,
      items: [],
    }
  },

  mounted() {
    this.getData()
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
  },

  methods: {
    getData() {
      axios.get('/consumables/select-types')
        .then((res) => {
          if (res && res.data && res.data.data) {
            this.items = res.data.data.types
          }
        })
        .catch((err) => {})
    }
  }
}
</script>

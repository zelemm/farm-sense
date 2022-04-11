<template>
  <SelectInput
    v-model="iValue"
    :items="items"
    :item-text="itemText"
    :item-value="itemValue"
    :errors="errors"
    :label="label"
    :loading="loading"
    :readonly="readonly"
    :disabled="disabled"
    :placeholder="placeholder"
  />
</template>

<script>
import axios from 'axios'

export default {
  components: {
    SelectInput: () => import('@/Common/Form/SelectInput'),
  },

  props: {
    value: {},
    url: {
      Type: String,
      default: '',
    },
    defaultFetch: {
        Type: Boolean,
        default: true,
    },
    itemText: {
      type: String,
      default: 'name',
    },
    itemValue: {
      type: String,
      default: 'id',
    },
    label: {
      type: String,
    },
    requestParams:  {
      Type: String,
      default: '',
    },
    loading: {Boolean, String},
    placeholder: String,
    errors: {String, Object},
    readonly: {String, Boolean},
    disabled: {Boolean, String},
  },
  data() {
    return {
      items: [],
    }
  },

  watch: {
    requestParams() {
      this.getDataFromApi()
    }
  },

  mounted() {
      if(this.defaultFetch)
        this.getDataFromApi()
  },

  computed: {
    iValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      }
    },
  },

  methods: {
    getDataFromApi() {
      if(this.url) {
        let url = this.url

        if(this.requestParams) {
          url += `?${this.requestParams}`
        }

        axios.get(url)
          .then((res) => {
            if (res && res.data && res.data.data && res.data.success && res.data.data.datas) {
              this.items = res.data.data.datas
            }
          })
          .catch((error) => {})
      } else {
        this.items = []
      }
    },
  },
}
</script>

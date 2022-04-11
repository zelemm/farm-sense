<template>
  <SelectInput
    v-model="iValue"
    :items="items"
    item-text="name"
    item-value="id"
    :errors="errors"
    :label="heading"
  />
</template>

<script>
import SelectInput from '@/Common/Form/SelectInput'
import axios from 'axios'

export default {
  components: {
    SelectInput,
  },

  props: {
    value: {},
    url: {
      Type: String,
      default: '',
    },
    heading: {
      type: String,
    },
    requestParams:  {
      Type: String,
      default: '',
    },
    errors: {
      Type: String,
      default: null,
    },
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
        let url = `/locations/${this.url}`

        if(this.requestParams) {
          url += `?${this.requestParams}`
        }

        axios.get(url)
          .then((res) => {
            if (res && res.data && res.data.data && res.data.data.datas) {
              this.items = res.data.data.datas
            }
          })
          .catch((error) => {
          })
      } else {
        this.items = []
      }
    },
  },
}
</script>

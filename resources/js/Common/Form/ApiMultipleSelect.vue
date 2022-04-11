<template>
  <div class="form-group">
    <label for="">{{ label }}</label>

    <v-autocomplete
      v-model="iValue"
      :items="items"
      :item-text="itemText"
      :item-value="itemValue"
      :placeholder="placeholder"
      :loading="loading"
      :search-input.sync="search"
      class="c-multiple-control"
      :no-data-text="lang.get('common.data_table.no_data')"
      multiple clearable chips small-chips single-line hide-details
    >
      <template v-if="items && items.length && !search" v-slot:prepend-item>
        <v-list-item
          ripple
          @click="selectAllItems"
        >
          <v-list-item-action>
            <v-icon :color="iValue && iValue.length > 0 ? 'indigo darken-4' : ''">
              {{ icon }}
            </v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              {{ 'common.select_all' | trans }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider class="mt-2" />
      </template>
    </v-autocomplete>

    <small v-if="hint" class="form-text text-muted">{{ hint }}</small>

    <div v-if="error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    value: {},
    defaultFetch: {
      Type: Boolean,
      default: true,
    },
    url: {
      Type: String,
      default: '',
    },
    errors: {String, Object},
    label: String,
    hint: String,
    itemText: {
      type: String,
      default: 'name',
    },
    itemValue: {
      type: String,
      default: 'id',
    },
    requestParams:  {
      Type: String,
      default: '',
    },
    placeholder: String,
  },

  data() {
    return {
      lang: window.lang,
      items: [],
      search: null,
      loading: false,
    }
  },

  computed: {
    iValue: {
      get() {
        if (this.value && this.value instanceof Array) {
          return this.value
        }

        return []
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

    selectedAllItems () {
      return this.iValue.length === this.items.length
    },

    selectedSomeItems () {
      return this.iValue.length > 0 && !this.selectedAllItems
    },

    icon () {
      if (this.selectedAllItems) return 'mdi-close-box'
      if (this.selectedSomeItems) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },
  },

  watch: {
    requestParams() {
      this.getDataFromApi()
    },
  },

  mounted() {
    if(this.defaultFetch)
      this.getDataFromApi()
  },

  methods: {
    getDataFromApi() {
      if(this.url) {
        let _this = this,
          url =  _this.url

        _this.loading = true

        if(_this.requestParams) {
          url += `?${_this.requestParams}`
        }

        axios.get(url)
          .then(res => {
            _this.loading = false
            if (res && res.data && res.data.data) {
              _this.items = res.data.data.datas || []
            }
          })
          .catch(() => {
            _this.loading = false
          })
      } else {
        _this.items = []
      }
    },

    selectAllItems () {
      let _this = this

      _this.$nextTick(() => {
        if (_this.selectedAllItems) {
          _this.iValue = []
        } else {
          _this.iValue = _this.items.map(function(item) {
            return item[_this.itemValue]
          })
        }
      })
    },
  },
}
</script>

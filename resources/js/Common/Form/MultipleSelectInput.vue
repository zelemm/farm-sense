<template>
  <div class="form-group">
    <label for="">{{ label }}</label>

    <v-autocomplete
      v-model="iValue"
      :items="items"
      :item-text="itemText"
      :item-value="itemValue"
      :placeholder="placeholder"
      class="c-multiple-control"
      :loading="loading"
      :search-input.sync="search"
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
        <v-divider class="mt-2"></v-divider>
      </template>
      <template #append>
        <slot name="append" />
        <!-- <v-btn
          class="ml-3"
          color="primary"
          small
        >
          {{ 'form.button.load_users' | trans }}
        </v-btn> -->
      </template>
    </v-autocomplete>

    <small v-if="hint" class="form-text text-muted">{{ hint }}</small>

    <div v-if="!hideMessages && error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    url: {
      Type: String,
      default: '',
    },
    errors: {String, Object},
    label: String,
    hint: String,
    items: Array,
    itemText: {
      type: String,
      default: 'name',
    },
    itemValue: {
      type: String,
      default: 'id',
    },
    requestParams: {
      Type: String,
      default: '',
    },
    loading: Boolean,
    placeholder: String,
    hideMessages: Boolean,
  },

  data() {
    return {
      lang: window.lang,
      search: null,
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
      return this.iValue && this.items && this.iValue.length === this.items.length
    },

    selectedSomeItems () {
      return this.iValue && this.iValue.length > 0 && !this.selectedAllItems
    },

    icon () {
      if (this.selectedAllItems) return 'mdi-close-box'
      if (this.selectedSomeItems) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },
  },

  methods: {
    selectAllItems () {
      let _this = this

      _this.$nextTick(() => {
        if (_this.selectedAllItems || !_this.items) {
          _this.iValue = []
        } else {
          _this.iValue = _this.items.map(function(item) {
            return item[_this.itemValue] || item
          })
        }
      })
    },
  }
}
</script>

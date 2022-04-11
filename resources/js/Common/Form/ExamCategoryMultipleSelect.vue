
<template>
  <div class="form-group">
    <label for="">{{ heading }}</label>

    <v-select
      v-model="iValue"
      :items="items"
      multiple clearable chips small-chips single-line
    >
      <template v-slot:prepend-item>
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
    </v-select>

    <div v-if="error" class="invalid-feedback">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    errors: {String, Object},
    heading: {
      type: String,
      default: window.lang.get('form.heading.exam_category')
    },
  },

  data() {
    return {
      lang: window.lang,
      items: [
        'qPCR', 'ELISA'
      ],
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

  methods: {
    selectAllItems () {
      this.$nextTick(() => {
        if (this.selectedAllItems) {
          this.iValue = []
        } else {
          this.iValue = this.items.map(function(item) {
            return item
          })
        }
      })
    },
  }
}
</script>

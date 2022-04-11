<template>
  <div class="form-group hide-message">
    <label>{{ label }}
      <v-icon class="ml-1" v-if="loading">mdi mdi-spin mdi-18px mdi-loading</v-icon>
    </label>

    <v-btn-toggle
      v-model="iValue"
      class="w-full mb-2"
    >
      <v-btn
        v-for="(type, t) in itemTypes"
        :key="`types-${t}`"
        :value="type"
        :class="{'is-active': type == iValue }"
      >
        {{ `form.heading.${type.toLowerCase()}` | trans }}
      </v-btn>
    </v-btn-toggle>

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
    label: {
      type: String,
      default: window.lang.get('form.heading.item_type')
    },
    readonly: {Boolean, String},
    disabled: {Boolean, String},
  },

  data() {
    return {
      loading: false,
      lang: window.lang,
    }
  },

  computed: {
    itemTypes() {
      return this.$store.getters['itemGroup/itemTypes']
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
    }
  },

  mounted() {
    if (!this.itemTypes || !this.itemTypes.length) {
      this.getItemTypes()
    }
  },

  methods: {
    getItemTypes() {
      this.loading = true

      this.$store.dispatch('itemGroup/getItemTypes')
        .then(() => {
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>

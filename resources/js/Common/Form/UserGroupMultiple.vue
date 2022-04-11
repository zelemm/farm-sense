<template>
  <MultipleSelectInput
    v-model="iValue"
    :items="user_groups || []"
    item-text="name"
    item-value="id"
    :errors="errors"
    :label="heading"
  />
</template>

<script>
export default {
  components: {
    MultipleSelectInput: () => import('@/Common/Form/MultipleSelectInput'),
  },

  props: {
    value: {},
    heading: {
      type: String,
      default: window.lang.get('form.filter.user_groups'),
    },
    errors: {
      Type: String,
      default: null,
    },
  },
  data() {
    return {
      lang: window.lang,
    }
  },

  computed: {
    user_groups() {
      return this.$store.getters['userGroup/user_groups']
    },

    iValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },

  mounted() {
    if (!this.user_groups) {
      this.getUserGroups()
    }
  },

  methods: {
    getUserGroups() {
      this.$store.dispatch('userGroup/getUserGroupList')
    },
  },
}
</script>

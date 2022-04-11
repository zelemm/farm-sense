<template>
  <SelectInput
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
    SelectInput: () => import('@/Common/Form/SelectInput'),
  },

  props: {
    value: {},
    url: {
      Type: String,
      default: '',
    },
    heading: {
      type: String,
      default: window.lang.get('form.heading.user_groups'),
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

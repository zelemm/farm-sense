<template>
  <div v-if="user">
    <UserForm :user="user" />
  </div>
</template>

<script>
export default {

  components: {
    UserForm: () => import('./Form.vue'),
  },

  computed: {
    user() {
      return this.$store.getters['auth/user']
    },
  },

  mounted() {
    if (!this.user) {
      this.getUserProfile()
    }
  },

  methods: {
    getUserProfile() {
        this.$store.dispatch('auth/getProfile')
    },
  },
}
</script>

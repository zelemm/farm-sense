<template>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title mt-0 mb-3 header-title">
        {{ 'form.alter' | trans }} {{ 'form.password' | trans }}
      </h5>

      <v-row class="mt-4">
        <v-col cols="12">
          <TextInput
            v-model="credentials.old_password"
            :label="lang.get('form.old_password')"
            type="password"
            :errors="errors.old_password"
          />
        </v-col>
        <v-col cols="12" md="12">
          <TextInput
            v-model="credentials.password"
            :label="lang.get('form.password')"
            type="password"
            :errors="errors.password"
          />
        </v-col>
        <v-col cols="12" md="12">
          <TextInput
            v-model="credentials.password_confirmation"
            :label="lang.get('form.confirm_password')"
            type="password"
            :errors="errors.password_confirmation"
          />
        </v-col>


        <v-col cols="12">
          <v-btn
            :loading="loadingSaving"
            color="primary"
            @click="changePassword"
            class="btn-round"
          >
            {{ 'form.button.save' | trans }}
            <icon name="rightArrow" class="ml-4" fill="#fff" />
          </v-btn>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  components: {
    TextInput: () => import('@/Common/Form/TextInput'),
    Icon: () => import('@/Shared/Icon'),
  },

  props: {
    userId: {
      type: [Number, null],
    },
  },

  data() {
    return {
      lang: window.lang,
      credentials: {},
      loadingSaving: false,
      errors: {},
    }
  },

  methods: {
    changePassword() {
      this.loadingSaving = true
      this.errors = {}

      axios
        .put(`/users/${this.userId}/change-password`, this.credentials)
        .then((res) => {
          this.loadingSaving = false
          this.errors = {}
          this.credentials = {}
          this.$notify({
            title: lang.get('form.success'),
            type: 'success',
            text: res.data.message,
          })
        })
        .catch((err) => {
          this.loadingSaving = false
          if (err.response && err.response.data.errors) {
            this.errors = err.response.data.errors
          }
        })
    },

  },
}
</script>

<style lang="scss" scoped>
.v-card__title {
  font-size: 0.875rem;
  font-weight: 500;
  text-transform: uppercase;
  color: #fff;
  letter-spacing: 0.0892857143em;
  line-height: 1rem;
}
.btn-round {
  border-radius: 25px;
}
</style>

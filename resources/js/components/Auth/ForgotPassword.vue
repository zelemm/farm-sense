<template>
  <div
    class="p-6 bg-indigo-200 min-h-screen flex items-center padding-left-100 bg-image-auth"
  >
    <div class="w-full max-w-sm">
      <form
        class="mt-8 bg-white rounded-xl shadow-xl overflow-hidden"
        @submit.prevent="submit"
      >
        <div class="px-10 py-12">
          <v-row class="position-relative">
            <v-col cols="5">
              <logo class="auth-logo mb-5" height="50" />
            </v-col>
            <v-col>
              <h1 class="font-bold text-2xl mt-6">
                  Sense Your Farm
              </h1>
            </v-col>
          </v-row>

          <div class="mb-4 text-sm text-gray-600">
            {{ 'common.auth.forgot_title' | trans }}
          </div>

          <div class="mt-4">
            <TextInput
              v-model="form.email"
              :label="lang.get('form.email')"
              type="email"
              :errors="errors.email"
            />
          </div>


          <div class="mt-6 flex justify-between">
            <v-btn
              depressed
              color="primary"
              :disabled="form.processing"
              type="submit"
              :loading="loadingForgotPassword"
            >
              <span v-if="$vuetify.breakpoint.xsOnly">
                {{ 'common.auth.reset_link_mobile' | trans }}
              </span>
              <span v-else>
                {{ 'common.auth.reset_link' | trans }}
              </span>
            </v-btn>
          </div>
        </div>
        <div
          class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex-between flex-wrap"
        >
          <router-link
            :to="{
              name: 'login',
            }"
            class="my-2"
          >
            {{ 'common.auth.back_login' | trans }}?
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  metaInfo: { title: lang.get('common.auth.forgot_pass') },

  components: {
    Logo: () => import('@/Shared/Logo'),
    TextInput: () => import('@/Common/Form/TextInput'),
  },

  data() {
    return {
      lang: lang,
      form: {
        email: '',
      },
      selectedLang: this.$cookies.get('lang') || 'pt',
      errors: {},
      loadingForgotPassword: false,
    }
  },

  methods: {
    submit() {
      this.loadingForgotPassword = true

      // clear errors
      this.errors = {}

      this.$store.dispatch('auth/forgotPassword', this.form)
        .then(() => {
          this.form.email = null
          this.loadingForgotPassword = false
        })
        .catch((err) => {
          this.loadingForgotPassword = false

          if (err.response && err.response.data && err.response.data.errors) {
            this.errors = err.response.data.errors
          }
        })
    },
  },
}
</script>
<style scoped>
@import './../../../css/main.css';

.forgot-password-row {
  border: 1px solid #dddddd87;
  border-radius: 10px;
  margin: 0 !important;
  box-shadow: -15px 27px 71px #00000010;
}
.forgot-password-row .col-12 {
  border-bottom: 1px solid #dddddd87;
  padding: 0 15px !important;
}
.forgot-password-row .col-12:last-child {
  border-bottom: none;
}
::v-deep .forgot-password-row label {
  margin: 0;
  padding-top: 10px;
  font-size: 12px;
}
::v-deep .forgot-password-row input {
  border: none;
  color: #000;
  padding: 0;
  height: 20px;
  font-size: 16px;
}
</style>

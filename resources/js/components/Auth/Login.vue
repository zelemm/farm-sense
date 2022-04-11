<template>
  <div
    class="p-6 bg-indigo-200 min-h-screen flex items-center padding-left-100 bg-image-auth"
  >
    <div class="w-full max-w-md">
      <form
        class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden"
        @submit.prevent="login"
      >
        <div class="px-10 py-5">
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

          <h1 class="font-bold text-blue text-3xl mt-0">
            {{ 'common.auth.login_title' | trans }}
          </h1>
          <div class="mb-4 text-sm text-gray-600">
            {{ 'common.auth.login_sub_title' | trans }}
          </div>

          <v-row>
            <v-col cols="12">
              <TextInput
                v-model="form.email"
                :label="lang.get('form.email')"
                :placeholder="lang.get('form.email')"
                type="email"
                :errors="errors.email"
              />
            </v-col>
            <v-col cols="12">
              <TextInput
                v-model="form.password"
                :label="lang.get('form.password')"
                type="password"
                :errors="errors.password"
              />
            </v-col>
          </v-row>

          <div class="flex-between flex-wrap mt-4">
            <v-checkbox
              v-model="form.remember"
              :label="lang.get('common.auth.remember_me')"
              value="true"
              hide-details
            />
          </div>
        </div>
        <div class="px-10 pt-0 flex-between flex-wrap">
          <v-btn
            depressed
            color="primary"
            type="submit"
            class="btn-round mb-2"
            :loading="loadingLogin"
            >{{ 'common.auth.login' | trans }}
            <icon class="ml-3" name="rightArrow" fill="#fff"></icon
          ></v-btn>
          <div class="create-account-sec mb-2">
            <router-link
              class="flex items-center group py-1 create-account"
              :to="{
                name: 'forgot-password',
              }"
            >
              {{ 'common.auth.forget' | trans }}?
              <icon class="ml-3" name="rightArrow" fill="#ccc"></icon>
            </router-link>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  metaInfo: { title: lang.get('common.auth.login') },

  components: {
    Icon: () => import('@/Shared/Icon'),
    Logo: () => import('@/Shared/Logo'),
    TextInput: () => import('@/Common/Form/TextInput'),
  },

  data() {
    return {
      errors: {},
      form: {
        email: '',
        password: '',
        remember: null,
      },
      lang: lang,
      loadingLogin: false,
      selectedLang: this.$cookies.get('lang') || 'en',
      errorTitle: null,
    }
  },

  methods: {
    login() {
      let _this = this
      _this.loadingLogin = true
      _this.errors = {}

      _this.$store
        .dispatch('auth/login', _this.form)
        .then(() => {
          _this.$store
            .dispatch('auth/getProfile')
            .then(user => {
              _this.loadingLogin = false
              _this.$router.push(
                localStorage.getItem('pathToLoadAfterLogin') || {
                  name: `${user.role}.dashboard`,
                }
              )
            })
            .catch(() => {
              _this.loadingLogin = false
            })
        })
        .catch(err => {
          _this.loadingLogin = false

          if (err.response && err.response.data && err.response.data.errors) {
            _this.errors = err.response.data.errors
          }
        })
    },
  },
}
</script>
<style scoped>
@import './../../../css/main.css';

.row.login-column {
  border: 1px solid #dddddd87;
  border-radius: 10px;
  margin: 0 !important;
  box-shadow: -4px 11px 30px #00000015;
}
.row.login-column .col-12 {
  border-bottom: 1px solid #dddddd87;
  padding: 0 15px !important;
}
.row.login-column .col-12:last-child {
  border-bottom: none;
}
::v-deep .row.login-column label {
  margin: 0;
  padding-top: 10px;
  font-size: 12px;
}
::v-deep .row.login-column input {
  border: none;
  color: #000;
  padding: 0;
  height: 20px;
  font-size: 16px;
}
.create-account {
  min-width: 64px;
  padding: 0 16px;
  height: -webkit-fill-available;
  display: block;
  box-shadow: 0 10px 87px -3px rgb(0 0 0 / 27%), 0 4px 6px -2px rgb(0 0 0 / 5%);
  border-radius: 20px;
  display: flex;
  align-items: center;
  color: #000;
}
.create-account-sec {
  height: 36px;
}
</style>

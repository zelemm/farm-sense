<script src="../../../router/superAdmin-routes.js"></script>
<template>
  <div>
    <v-overlay :value="loading">
      <v-progress-circular
        indeterminate
        size="64"
      />
    </v-overlay>

    <v-row class="page-title align-center">
      <v-col cols="12" class="flex-between flex-wrap">
        <h4 class="mb-1 mt-0">
          {{ farm_google.id ? 'form.button.edit' : 'form.heading.create' | trans }} {{ 'menu.farm_google' | trans }}
        </h4>

        <nav aria-label="breadcrumb" class="mt-1">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link :to="{ name: `${authUser.role}.farm_google` }">{{ 'menu.farm_google' | trans }}</router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              {{ farm_google.id ? farm_google.label : 'form.heading.create' | trans }}
            </li>
          </ol>
        </nav>
      </v-col>
    </v-row>

    <trashed-message v-if="farm_google.id && farm_google.deleted_at" class="mb-6" @restore="restore">
      {{ 'common.farm_google_deleted' | trans }}
    </trashed-message>

    <v-row class="mt-0">
      <v-col cols="12">
        <div class="card">
          <div class="card-body">
            <form @submit.stop.prevent="submit">
              <v-tabs
                next-icon="mdi-arrow-right-bold-box-outline"
                prev-icon="mdi-arrow-left-bold-box-outline"
                class="rounded-sm custom-tab"
                show-arrows
              >
                <v-tabs-slider />
                <v-tab
                  class="tab-item"
                  key="Cattle details"
                  :class="{ 'has-errors': profileError }"
                >
                  {{ 'common.farm_google_details' | trans }}
                </v-tab>

                <v-tab-item>
                  <v-row class="mt-3">
                      <v-col cols="12" md="6">
                          <ApiSelect
                              v-model="farm_google.farm_id"
                              :label="lang.get('menu.farms')"
                              url="/farms/list"
                              :errors="errors.farm_id"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextInput
                              v-model="farm_google.label"
                              :label="lang.get('form.label')"
                              :errors="errors.label"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextInput
                              v-model="farm_google.organisation_id"
                              :label="lang.get('form.organisation_id')"
                              :errors="errors.organisation_id"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextAreaInput
                              :label="lang.get('form.scope')"
                              v-model="farm_google.scope"
                              :errors="errors.scope"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextAreaInput
                              :label="lang.get('form.client_id')"
                              v-model="farm_google.client_id"
                              :errors="errors.client_id"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextAreaInput
                              :label="lang.get('form.client_secret')"
                              v-model="farm_google.client_secret"
                              :errors="errors.client_secret"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextInput
                              v-model="farm_google.timezone"
                              :label="lang.get('form.timezone')"
                              :errors="errors.timezone"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <SelectInput
                              v-model="farm_google.status"
                              :label="lang.get('form.status')"
                              :items="status"
                              item-text="name"
                              item-value="value"
                              :errors="errors.status"
                          />
                      </v-col>

                  </v-row>
                </v-tab-item>

              </v-tabs>

              <div class="flex-wrap" :class="farm_google.id && !farm_google.deleted_at ? 'flex-between' : 'flex-end'">
                <button v-if="farm_google.id && !farm_google.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">
                  {{ 'form.farm_google_lang.delete' | trans }}
                </button>

                <div>
                  <v-btn :loading="loading" color="primary" type="submit">
                    <span v-if="farm_google.id">{{ 'form.farm_google_lang.update' | trans }}</span>
                    <span v-else>{{ 'form.farm_google_lang.create' | trans }}</span>
                  </v-btn>
                </div>
              </div>
            </form>
          </div>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import moment from 'moment'
export default {
  name: 'FarmGoogleForm',
  remember: 'form',

  components: {
    Icon: () => import('@/Shared/Icon'),
    SelectInput: () => import('@/Common/Form/SelectInput'),
    TextAreaInput: () => import('@/Common/Form/TextAreaInput'),
    TextInput: () => import('@/Common/Form/TextInput'),
    TrashedMessage: () => import('@/Shared/TrashedMessage'),
    ApiSelect: () => import('@/Common/Form/ApiSelect'),
  },

  props: {
    farm_google: Object,
  },

  data() {
    return {
      errors: {},
      lang: lang,
      loading: false,
      form: {
      },
      photoPreview: null,
    }
  },

  computed: {

    status() {
      return this.$store.getters['common/farmGoogleStatus']
    },

    profileError() {
      let locationFields = [
        'label',
        'organisation_id',
        'farm_id',
        'client_id',
        'client_secret',
        'scope',
        'status',
      ]

      for (let field of locationFields) {
        if (this.errors[field]) {
          return true
        }
      }

      return false
    },
  },

  mounted() {

    if (!this.status || !this.status.length) {
      this.getStatus()
    }
  },

  methods: {
    submit() {
      let _this = this
      _this.loading = true
      _this.errors = {}
      let formData = new FormData()

      for (const i in _this.farm_google) {
        if (_this.farm_google[i] !== '' && _this.farm_google[i] !== null && _this.farm_google[i] !== 'undefined') {
            formData.append(i, _this.farm_google[i])
        }
      }

      if (_this.farm_google.id) {
        formData.append('id', _this.farm_google.id)
        formData.append('_method', 'PUT')
      }

      let action = _this.farm_google.id ? 'farmGoogle/update' : 'farmGoogle/create'

      _this.$store.dispatch(action, formData)
        .then(data => {
          _this.loading = false
          _this.errors = {}
            if (!_this.farm_google.id && data && data.farm_google_id) {
                this.$router.push({
                    name: `${this.authUser.role}.farm_google.edit`,
                    params: {
                        id: data.farm_google_id
                    }
                })
            } else {
                this.farm_google.status = data.farm_google.status
                this.farm_google.notes = data.farm_google.notes
            }

        })
        .catch(err => {
          _this.loading = false

          if (err.response && err.response.data && err.response.data.errors) {
            _this.errors = err.response.data.errors
          }
        })
    },

   destroy() {
      if (confirm(`${lang.get('form.message.conf_del')} ${lang.get('menu.farm_google')}?`)) {
        let _this = this

        _this.$store.dispatch('farmGoogle/delete', _this.farm_google.id)
          .then(() => {
            _this.farm_google.deleted_at = true
          })
          .catch(() => {})
      }
    },

    restore() {
      if (confirm(`${lang.get('form.message.conf_restore')} ${lang.get('menu.farm_google')}?`)) {
        let _this = this

        _this.$store.dispatch('farmGoogle/restore', _this.farm_google.id)
          .then(() => {
            _this.farm_google.deleted_at = null
          })
          .catch(() => {})
      }
    },

    getStatus() {
      this.loading = true

      this.$store.dispatch('common/getFarmGoogleStatus')
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

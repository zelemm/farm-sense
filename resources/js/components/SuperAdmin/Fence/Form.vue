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
          {{ farm_fence.id ? 'form.button.edit' : 'form.heading.create' | trans }} {{ 'menu.farm_fence' | trans }}
        </h4>

        <nav aria-label="breadcrumb" class="mt-1">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link :to="{ name: `${authUser.role}.farm_fence` }">{{ 'menu.farm_fence' | trans }}</router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              {{ farm_fence.id ? farm_fence.label : 'form.heading.create' | trans }}
            </li>
          </ol>
        </nav>
      </v-col>
    </v-row>

    <trashed-message v-if="farm_fence.id && farm_fence.deleted_at" class="mb-6" @restore="restore">
      {{ 'common.farm_fence_deleted' | trans }}
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
                  key="Fence details"
                  class="tab-item"
                  :class="{ 'has-errors': profileError }"
                >
                  {{ 'common.farm_fence_details' | trans }}
                </v-tab>
                <v-tab
                  v-if="farm_fence.id"
                  key="Fence Co-ordinates"
                  class="tab-item"
                  :class="{ 'has-errors': profileError }"
                >
                  {{ 'common.farm_fence_coordinates' | trans }}
                </v-tab>

                <v-tab-item>
                  <v-row class="mt-3">
                    <v-col cols="12" md="6">
                      <ApiSelect
                        v-model="farm_fence.farm_id"
                        :label="lang.get('menu.farms')"
                        url="/farms/list"
                        :errors="errors.farm_id"
                      />
                    </v-col>

                    <v-col cols="12" md="6">
                      <TextInput
                        v-model="farm_fence.label"
                        :label="lang.get('form.label')"
                        :errors="errors.label"
                      />
                    </v-col>

                    <v-col cols="12" md="6">
                      <TextAreaInput
                        v-model="farm_fence.description"
                        :label="lang.get('form.description')"
                        :errors="errors.description"
                      />
                    </v-col>
                  </v-row>
                </v-tab-item>

                <v-tab-item v-if="farm_fence.id">
                  <div class="card">
                    <div class="card-body">
                      <ApiDatatable
                        ref="coordinateTable"
                        :show-filter="true"
                        :show-status="false"
                        :headers="coordinateHeaders"
                        :heading="lang.get('form.coordinates')"
                        filter-flex="flex-end"
                        :url="`/super_admin/farm_fence/${farm_fence.id}/coordinates`"
                      />
                    </div>
                  </div>

                  <template>
                    <div class="py-4">
                      <v-btn
                        color="primary"
                        dark
                        @click="addItem('people')"
                      >
                        {{ 'form.farm_fence_lang.add_coordinate' | trans }}
                      </v-btn>
                    </div>

                    <div class="clearfix" />

                  </template>
                </v-tab-item>
              </v-tabs>

              <div class="flex-wrap" :class="farm_fence.id && !farm_fence.deleted_at ? 'flex-between' : 'flex-end'">
                <button v-if="farm_fence.id && !farm_fence.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">
                  {{ 'form.farm_fence_lang.delete' | trans }}
                </button>

                <div>
                  <v-btn :loading="loading" color="primary" type="submit">
                    <span v-if="farm_fence.id">{{ 'form.farm_fence_lang.update' | trans }}</span>
                    <span v-else>{{ 'form.farm_fence_lang.create' | trans }}</span>
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
  name: 'FarmFenceForm',
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
    farm_fence: Object,
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
    coordinateHeaders() {
      return [
          { text: this.lang.get('form.heading.id'), value: 'id' },
          { text: this.lang.get('menu.longitude'), value: 'farm.longitude' },
          { text: this.lang.get('form.latitude'), value: 'latitude' },
          { text: this.lang.get('form.created_at'), value: 'created_at' },
      ]
    },

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

      for (const i in _this.farm_fence) {
        if (_this.farm_fence[i] !== '' && _this.farm_fence[i] !== null && _this.farm_fence[i] !== 'undefined') {
            formData.append(i, _this.farm_fence[i])
        }
      }

      if (_this.farm_fence.id) {
        formData.append('id', _this.farm_fence.id)
        formData.append('_method', 'PUT')
      }

      let action = _this.farm_fence.id ? 'farmFence/update' : 'farmFence/create'

      _this.$store.dispatch(action, formData)
        .then(data => {
          _this.loading = false
          _this.errors = {}
            if (!_this.farm_fence.id && data && data.farm_fence_id) {
                this.$router.push({
                    name: `${this.authUser.role}.farm_fence.edit`,
                    params: {
                        id: data.farm_fence_id
                    }
                })
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
      if (confirm(`${lang.get('form.message.conf_del')} ${lang.get('menu.farm_fence')}?`)) {
        let _this = this

        _this.$store.dispatch('farmFence/delete', _this.farm_fence.id)
          .then(() => {
            _this.farm_fence.deleted_at = true
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

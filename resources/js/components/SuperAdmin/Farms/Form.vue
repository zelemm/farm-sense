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
          {{ farm.id ? 'form.button.edit' : 'form.heading.create' | trans }} {{ 'menu.farms' | trans }}
        </h4>

        <nav aria-label="breadcrumb" class="mt-1">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link :to="{ name: `${authUser.role}.farms` }">{{ 'menu.farms' | trans }}</router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              {{ farm.id ? farm.name : 'form.heading.create' | trans }}
            </li>
          </ol>
        </nav>
      </v-col>
    </v-row>

    <trashed-message v-if="farm.id && farm.deleted_at" class="mb-6" @restore="restore">
      {{ 'common.farm_deleted' | trans }}
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
                  key="Farm details"
                  :class="{ 'has-errors': profileError }"
                >
                  {{ 'common.farm_details' | trans }}
                </v-tab>

                <v-tab-item>
                  <v-row class="mt-3">

                    <v-col cols="12" md="6">
                      <TextInput
                        v-model="farm.name"
                        :label="lang.get('form.name')"
                        :errors="errors.name"
                      />
                    </v-col>

                      <v-col cols="12" md="6">
                          <TextInput
                              v-model="farm.phone"
                              :label="lang.get('form.phone')"
                              type="tel"
                              :errors="errors.phone"
                          />
                      </v-col>

                    <v-col cols="12" md="6">
                      <TextInput
                        v-model="farm.address"
                        :label="lang.get('form.address')"
                        :errors="errors.address"
                      />
                    </v-col>

                      <v-col cols="12" md="6">
                          <TextInput
                              v-model="farm.api_key"
                              :label="lang.get('form.api_key')"
                              :errors="errors.api_key"
                          />
                      </v-col>

                    <v-col cols="12" md="6">
                      <SelectInput
                        v-model="farm.status"
                        :label="lang.get('form.status')"
                        :items="status"
                        item-text="name"
                        item-value="value"
                        :errors="errors.status"
                      />
                    </v-col>

                    <v-col cols="12" md="6">
                        <TextAreaInput
                            v-model="farm.notes"
                            :errors="errors.notes"
                        />
                    </v-col>
                      <v-col cols="12" md="6">
                          <MultiImages
                              v-model="farm.attachs"
                              :errors="attachsError"
                          />
                      </v-col>
                      <v-col cols="12" v-if="farm.images && farm.images.length">
                          <h4 class="mb-4">{{ 'form.heading.images' | trans }}:</h4>

                          <div class="flex-start flex-wrap images-list mb-4">
                              <div
                                  v-for="(image, i) in farm.images"
                                  :key="`images-${i}`"
                                  class="mx-1 mb-1 relative images-item"
                                  role="button"
                              >
                                  <template v-if="isImage(image.path_url)">
                                      <v-img
                                          :lazy-src="image.path_url"
                                          height="150"
                                          :src="image.path_url"
                                          @click="showImage(image.path_url)"
                                      ></v-img>
                                      <v-icon
                                          class="remove-image-icon"
                                          color="white"
                                          @click.stop.prevent="removeImage(i)"
                                          :title="lang.get('form.button.delete')"
                                      >mdi-delete</v-icon>
                                  </template>
                                  <template v-else-if="image.path">
                                      <p>
                                          <strong>{{ 'form.heading.file_name' | trans }}:</strong> {{ image.path }}
                                          <v-icon
                                              color="red"
                                              @click.stop.prevent="removeImage(i)"
                                              :title="lang.get('form.button.delete')"
                                              size="18"
                                          >mdi-delete</v-icon>
                                      </p>
                                  </template>
                              </div>
                          </div>
                      </v-col>

                  </v-row>
                </v-tab-item>

              </v-tabs>

              <div class="flex-wrap" :class="farm.id && !farm.deleted_at ? 'flex-between' : 'flex-end'">
                <button v-if="farm.id && !farm.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">
                  {{ 'form.farm_lang.delete' | trans }}
                </button>

                <div>
                  <v-btn :loading="loading" color="primary" type="submit">
                    <span v-if="farm.id">{{ 'form.farm_lang.update' | trans }}</span>
                    <span v-else>{{ 'form.farm_lang.create' | trans }}</span>
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
export default {
  name: 'FarmForm',
  remember: 'form',

  components: {
    Icon: () => import('@/Shared/Icon'),
    SelectInput: () => import('@/Common/Form/SelectInput'),
    TextAreaInput: () => import('@/Common/Form/TextAreaInput'),
    TextInput: () => import('@/Common/Form/TextInput'),
    TrashedMessage: () => import('@/Shared/TrashedMessage'),
    MultiImages: () => import('@/Common/Form/MultiImages'),
  },

  props: {
    farm: Object,
  },

  data() {
    return {
      errors: {},
      lang: lang,
      loading: false,
      form: {
        name: this.farm.name ?? '',
        phone: this.farm.phone ?? '',
        address: this.farm.address ?? '',
        status: this.farm.id ? this.farm.status : 'Active',
        notes: this.farm.notes ?? '',
      },
      photoPreview: null,
    }
  },

  computed: {
    authUser() {
      return this.$store.getters['auth/user']
    },

    status() {
      return this.$store.getters['common/farmStatus']
    },

    profileError() {
      let locationFields = [
        'name',
        'address',
        'phone',
        'status',
      ]

      for (let field of locationFields) {
        if (this.errors[field]) {
          return true
        }
      }

      return false
    },
    attachsError() {
      if (this.errors.images && this.errors.images[0]) {
          return this.errors.images[0]
      }

      if (this.farm.images && this.farm.images.length) {
          for (let i = 0; i < this.farm.images.length; i++) {
              if (this.errors[`images.${i}`] && this.errors[`images.${i}`][0]) {
                  return this.errors[`images.${i}`]
              }
          }
      }

      return null;
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

      for (const i in _this.farm) {
        if (_this.farm[i] !== '' && _this.farm[i] !== null && _this.farm[i] !== 'undefined') {
            if (i == 'attachs') {
                continue
            } else if (i == 'images') {
                formData.append('old_images', JSON.stringify(_this.farm.images))
            } else {
                formData.append(i, _this.farm[i])
            }
        }
      }
      if (_this.farm.attachs && _this.farm.attachs.length > 0) {
        for (const file in _this.farm.attachs) {
            formData.append(`images[${file}]`, _this.farm.attachs[file])
        }
      }

      if (_this.farm.id) {
        formData.append('id', _this.farm.id)
        formData.append('_method', 'PUT')
      }

      let action = _this.farm.id ? 'farms/update' : 'farms/create'

      _this.$store.dispatch(action, formData)
        .then(data => {
          _this.loading = false
          _this.errors = {}
            if (!_this.farm.id && data && data.farm_id) {
                this.$router.push({
                    name: `${this.authUser.role}.farms.edit`,
                    params: {
                        id: data.farm_id
                    }
                })
            } else {
                this.farm.status = data.farm.status
                this.farm.notes = data.farm.notes
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
      if (confirm(`${lang.get('form.message.conf_del')} ${lang.get('menu.farms')}?`)) {
        let _this = this

        _this.$store.dispatch('farms/delete', _this.farm.id)
          .then(() => {
            _this.farm.deleted_at = true
          })
          .catch(() => {})
      }
    },

    restore() {
      if (confirm(`${lang.get('form.message.conf_restore')} ${lang.get('menu.farms')}?`)) {
        let _this = this

        _this.$store.dispatch('farms/restore', _this.farm.id)
          .then(() => {
            _this.farm.deleted_at = null
          })
          .catch(() => {})
      }
    },

    getStatus() {
      this.loading = true

      this.$store.dispatch('common/getFarmStatus')
        .then(() => {
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },

    removeImage(index) {
      this.farm.images.splice(index, 1)
    },

    showImage(url) {
      this.imageUrl = url
      this.imageModal = true
    },

    isImage(path) {
      if(!path || typeof path !== 'string') return false;

      return path.match(/^http[^\?]*.(jpg|jpeg|gif|png|tiff)(\?(.*))?$/g) !== null
    },

  },
}
</script>
<style lang="scss" scoped>
.remove-image-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: .5;
    z-index: 1;
    width: 18px;
    height: 18px;
    border-radius: 5px;
    font-size: 14px;
    background-color: red;
    &:hover {
        opacity: 1;
    }
}
.close-dialog-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
}
.images-list {
    .images-item {
        width: 250px;
        @media (max-width: 767px) {
            width: calc(50% - 8px);
            .v-image {
                height: 120px !important;
            }
        }
    }
}
</style>

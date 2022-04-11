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
          {{ cattle.id ? 'form.button.edit' : 'form.heading.create' | trans }} {{ 'menu.cattle' | trans }}
        </h4>

        <nav aria-label="breadcrumb" class="mt-1">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link :to="{ name: `${authUser.role}.cattle` }">{{ 'menu.cattle' | trans }}</router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              {{ cattle.id ? cattle.name : 'form.heading.create' | trans }}
            </li>
          </ol>
        </nav>
      </v-col>
    </v-row>

    <trashed-message v-if="cattle.id && cattle.deleted_at" class="mb-6" @restore="restore">
      {{ 'common.cattle_deleted' | trans }}
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
                  {{ 'common.cattle_details' | trans }}
                </v-tab>

                <v-tab-item>
                  <v-row class="mt-3">
                      <v-col cols="12" md="6">
                          <ApiSelect
                              v-model="cattle.farm_id"
                              :label="lang.get('menu.farms')"
                              url="/farms/list"
                              :errors="errors.farm_id"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <SelectInput
                              v-model="cattle.cattle_type"
                              :label="lang.get('form.cattle_type')"
                              :items="cattleType"
                              item-text="name"
                              item-value="value"
                              :errors="errors.cattle_type"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextInput
                              v-model="cattle.mac_id"
                              :label="lang.get('form.mac_id')"
                              :errors="errors.mac_id"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <SelectInput
                              v-model="cattle.breed"
                              :label="lang.get('form.breed')"
                              :items="breed"
                              item-text="name"
                              item-value="value"
                              :errors="errors.breed"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextInput
                              v-model="cattle.name"
                              :label="lang.get('form.name')"
                              :errors="errors.name"
                          />
                      </v-col>
                      <v-col cols="12" md="6">
                          <DateSelect
                              v-model="cattle.date_of_birth"
                              :label="lang.get('form.date_of_birth')"
                              :errors="errors.date_of_birth"
                              :maxDate="maxBirthDate"
                          />
                      </v-col>
                      <v-col cols="12" md="6">
                          <TextInput
                              type="tel"
                              v-model="cattle.weight"
                              :label="lang.get('form.weight')"
                              :errors="errors.weight"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <SelectInput
                              v-model="cattle.sex"
                              :label="lang.get('form.sex')"
                              :items="sex"
                              item-text="name"
                              item-value="value"
                              :errors="errors.sex"
                          />
                      </v-col>
                      <template v-if="cattle.sex == 'Sire'">
                          <v-col cols="12" md="6">
                              <TextInput
                                  v-model="cattle.casterated"
                                  :label="lang.get('form.casterated')"
                                  :errors="errors.casterated"
                              />
                          </v-col>
                      </template>

                      <v-col cols="12" md="6">
                          <SelectInput
                              v-model="cattle.arrival"
                              :label="lang.get('form.arrival')"
                              :items="arrivalStatus"
                              item-text="name"
                              item-value="value"
                              :errors="errors.arrival"
                          />
                      </v-col>
                      <template v-if="cattle.arrival == 'Direct from Auction' || cattle.arrival == 'Direct from Farm'">
                          <v-col cols="12" md="6">
                              <TextInput
                                  v-model="cattle.vendor"
                                  :label="lang.get('form.vendor')"
                                  :errors="errors.vendor"
                              />
                          </v-col>

                          <v-col cols="12" md="6">
                              <TextInput
                                  v-model="cattle.purchase_price"
                                  :label="lang.get('form.purchase_price')"
                                  :errors="errors.purchase_price"
                              />
                          </v-col>
                      </template>

                      <v-col cols="12" md="6">
                          <SelectInput
                              v-model="cattle.status"
                              :label="lang.get('form.status')"
                              :items="status"
                              item-text="name"
                              item-value="value"
                              :errors="errors.status"
                          />
                      </v-col>

                      <template v-if="cattle.status == 'Sold'">
                          <v-col cols="12" md="6">
                              <TextInput
                                  v-model="cattle.sold_price"
                                  :label="lang.get('form.sold_price')"
                                  :errors="errors.sold_price"
                              />
                          </v-col>

                          <v-col cols="12" md="6">
                              <DateSelect
                                  v-model="cattle.date_of_sell"
                                  :label="lang.get('form.date_of_sell')"
                                  :errors="errors.date_of_sell"
                              />
                          </v-col>
                      </template>

                      <template v-if="cattle.status == 'Dead'">
                          <v-col cols="12" md="6">
                              <DateSelect
                                  v-model="cattle.date_of_death"
                                  :label="lang.get('form.date_of_death')"
                                  :errors="errors.date_of_death"
                              />
                          </v-col>
                      </template>

                      <v-col cols="12" md="6">
                          <ApiSelect
                              v-model="cattle.mother_cow"
                              :label="lang.get('form.mother_cow')"
                              :url="`/cattle/list?cow_id=${cattle.id?cattle.id:''}`"
                              :errors="errors.mother_cow"
                          />
                      </v-col>
                      <v-col cols="12" md="6">
                          <ApiSelect
                              v-model="cattle.father_cow"
                              :label="lang.get('form.father_cow')"
                              :url="`/cattle/list?cow_id=${cattle.id?cattle.id:''}`"
                              :errors="errors.father_cow"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <TextAreaInput
                              v-model="cattle.notes"
                              :errors="errors.notes"
                          />
                      </v-col>

                      <v-col cols="12" md="6">
                          <MultiImages
                              v-model="cattle.attachs"
                              :errors="attachsError"
                          />
                      </v-col>
                      <v-col cols="12" v-if="cattle.images && cattle.images.length">
                          <h4 class="mb-4">{{ 'form.heading.images' | trans }}:</h4>

                          <div class="flex-start flex-wrap images-list mb-4">
                              <div
                                  v-for="(image, i) in cattle.images"
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

              <div class="flex-wrap" :class="cattle.id && !cattle.deleted_at ? 'flex-between' : 'flex-end'">
                <button v-if="cattle.id && !cattle.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">
                  {{ 'form.cattle_lang.delete' | trans }}
                </button>

                <div>
                  <v-btn :loading="loading" color="primary" type="submit">
                    <span v-if="cattle.id">{{ 'form.cattle_lang.update' | trans }}</span>
                    <span v-else>{{ 'form.cattle_lang.create' | trans }}</span>
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
  name: 'CattleForm',
  remember: 'form',

  components: {
    Icon: () => import('@/Shared/Icon'),
    SelectInput: () => import('@/Common/Form/SelectInput'),
    TextAreaInput: () => import('@/Common/Form/TextAreaInput'),
    TextInput: () => import('@/Common/Form/TextInput'),
    TrashedMessage: () => import('@/Shared/TrashedMessage'),
    MultiImages: () => import('@/Common/Form/MultiImages'),
    DateSelect: () => import('@/Common/Form/DateSelect'),
    ApiSelect: () => import('@/Common/Form/ApiSelect'),
  },

  props: {
    cattle: Object,
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

    maxBirthDate() {
      return moment().format('YYYY-MM-DD')
    },

    status() {
      return this.$store.getters['common/cattleStatus']
    },

    arrivalStatus() {
      return this.$store.getters['common/cattleArrivalStatus']
    },

    breed() {
      return this.$store.getters['common/cattleBreed']
    },

    sex() {
      return this.$store.getters['common/cattleSex']
    },

    cattleType() {
      return this.$store.getters['common/cattleType']
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

      if (this.cattle.images && this.cattle.images.length) {
          for (let i = 0; i < this.cattle.images.length; i++) {
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

      if (!this.arrivalStatus || !this.arrivalStatus.length) {
          this.getArrivalStatus()
      }

      if (!this.breed || !this.breed.length) {
          this.getBreed()
      }

      if (!this.sex || !this.sex.length) {
          this.getCattleSex()
      }

      if (!this.cattleType || !this.cattleType.length) {
          this.getCattleType()
      }
  },

  methods: {
    submit() {
      let _this = this
      _this.loading = true
      _this.errors = {}
      let formData = new FormData()

      for (const i in _this.cattle) {
        if (_this.cattle[i] !== '' && _this.cattle[i] !== null && _this.cattle[i] !== 'undefined') {
            if (i == 'attachs') {
                continue
            } else if (i == 'images') {
                formData.append('old_images', JSON.stringify(_this.cattle.images))
            } else {
                formData.append(i, _this.cattle[i])
            }
        }
      }
      if (_this.cattle.attachs && _this.cattle.attachs.length > 0) {
        for (const file in _this.cattle.attachs) {
            formData.append(`images[${file}]`, _this.cattle.attachs[file])
        }
      }

      if (_this.cattle.id) {
        formData.append('id', _this.cattle.id)
        formData.append('_method', 'PUT')
      }

      let action = _this.cattle.id ? 'cattle/update' : 'cattle/create'

      _this.$store.dispatch(action, formData)
        .then(data => {
          _this.loading = false
          _this.errors = {}
            if (!_this.cattle.id && data && data.cattle_id) {
                this.$router.push({
                    name: `${this.authUser.role}.cattle.edit`,
                    params: {
                        id: data.cattle_id
                    }
                })
            } else {
                this.cattle.status = data.cattle.status
                this.cattle.notes = data.cattle.notes
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
      if (confirm(`${lang.get('form.message.conf_del')} ${lang.get('menu.cattle')}?`)) {
        let _this = this

        _this.$store.dispatch('cattle/delete', _this.cattle.id)
          .then(() => {
            _this.cattle.deleted_at = true
          })
          .catch(() => {})
      }
    },

    restore() {
      if (confirm(`${lang.get('form.message.conf_restore')} ${lang.get('menu.cattle')}?`)) {
        let _this = this

        _this.$store.dispatch('cattle/restore', _this.cattle.id)
          .then(() => {
            _this.cattle.deleted_at = null
          })
          .catch(() => {})
      }
    },

    getStatus() {
      this.loading = true

      this.$store.dispatch('common/getCattleStatus')
        .then(() => {
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },

    getArrivalStatus() {
      this.loading = true

      this.$store.dispatch('common/getCattleArrivalStatus')
          .then(() => {
              this.loading = false
          })
          .catch(() => {
              this.loading = false
          })
    },

    getBreed() {
      this.loading = true

      this.$store.dispatch('common/getCattleBreed')
          .then(() => {
              this.loading = false
          })
          .catch(() => {
              this.loading = false
          })
    },

    getCattleSex() {
      this.loading = true

      this.$store.dispatch('common/getCattleSex')
          .then(() => {
              this.loading = false
          })
          .catch(() => {
              this.loading = false
          })
    },

    getCattleType() {
      this.loading = true

      this.$store.dispatch('common/getCattleType')
          .then(() => {
              this.loading = false
          })
          .catch(() => {
              this.loading = false
          })
    },

    removeImage(index) {
      this.cattle.images.splice(index, 1)
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

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
                        :map-adjust="true"
                        :show-filter="true"
                        :show-status="false"
                        :headers="coordinateHeaders"
                        :heading="lang.get('form.coordinates')"
                        filter-flex="flex-end"
                        :url="`/super_admin/farm_fence/${farm_fence.id}/coordinates`"
                        @handleFenceEdit="handleGoogleFenceAdd"
                        @openDeleteFenceItem="openDeleteFenceItem"
                        @restoreFenceItem="restoreFenceItem"
                        @showGoogle="adjustFarmFenceEdit"
                        @adjustFarmFence="adjustFarmFence"
                        @openAddColorModal="openAddColorModal"
                      />
                    </div>
                  </div>

                  <!--                  <template>-->
                  <!--                    <div class="py-4">-->
                  <!--                      <v-btn-->
                  <!--                        color="primary"-->
                  <!--                        dark-->
                  <!--                        @click="handleGoogleFenceAdd()"-->
                  <!--                      >-->
                  <!--                        {{ 'form.farm_fence_lang.add_coordinate' | trans }}-->
                  <!--                      </v-btn>-->
                  <!--                    </div>-->

                  <!--                    <div class="clearfix" />-->
                  <!--                  </template>-->
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
    <div class="clearfix" />
    <template v-if="farm_fence.id">
      <v-row justify="center">
        <v-dialog
          v-model="isShowAddColorModal"
          max-width="1000"
        >
          <div class="card mb-0">
            <v-btn
              class="close-dialog-icon"
              icon
              :title="lang.get('form.button.close')"
              @click.native="isShowAddColorModal = false"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>

            <div class="card-body">
              <h5 class="card-title mt-0 mb-4 header-title">
                {{ 'form.farm_fence_coords_lang.add_color' | trans }}
              </h5>

              <form @submit.stop.prevent="submit">
                <v-row class="mt-4">
                  <v-col cols="12" md="6">
                    <v-color-picker v-model="farmFenceCoordinateColor"
                                    :errors="farmFenceCoordinateColorError"
                                    flat hide-mode-switch
                    />
                  </v-col>
                </v-row>

                <div class="flex-between flex-wrap w-full mt-4">
                  <v-btn
                    color="primary"
                    class="btn-round"
                    :loading="loadingSaveDetail"
                    @click.native="saveFenceCoordinateColor"
                  >
                    {{ 'form.button.save' | trans }}
                  </v-btn>
                  <v-btn
                    class="mr-2 text-none my-2 btn-round"
                    elevation-0
                    text
                    @click.native="isShowAddColorModal = false"
                  >
                    {{ 'common.no_back' | trans }}
                  </v-btn>
                </div>
              </form>
            </div>
          </div>
        </v-dialog>
      </v-row>

      <v-row justify="center">
        <v-dialog
          v-model="isShowDeleteFenceModal"
          max-width="400"
        >
          <div class="card mb-0">
            <v-btn
              class="close-dialog-icon"
              icon
              :title="lang.get('form.button.close')"
              @click.native="isShowDeleteFenceModal = false"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>

            <div class="card-body">
              <form @submit.stop.prevent="submit">
                <v-row class="mt-4">
                  <v-col cols="12" md="12">
                    Really want to delete Fence co-ordinates
                  </v-col>
                </v-row>
                <div class="flex-between flex-wrap w-full mt-4">
                  <v-btn
                    color="primary"
                    class="btn-round"
                    :loading="loadingSaveDetail"
                    @click.native="deleteFenceDetail"
                  >
                    {{ 'form.button.confirm' | trans }}
                  </v-btn>
                  <v-btn
                    class="mr-2 text-none my-2 btn-round"
                    elevation-0
                    text
                    @click.native="isShowDeleteFenceModal = false"
                  >
                    {{ 'common.no_back' | trans }}
                  </v-btn>
                </div>
              </form>
            </div>
          </div>
        </v-dialog>
      </v-row>
      <v-row justify="center">
        <v-dialog
          v-if="googleMap.lat && googleMap.lng"
          v-model="showGoogleMapDialog"
          max-width="800"
        >
          <v-card class="google-map-dialog">
            <v-card-title class="justify-between bg-blue-500 text-white">
              <span class="headline_googlemap">{{ googleMap.farm_name }}, {{ googleMapAddress }}</span>
              <v-btn
                class="close-dialog-icon"
                icon
                :title="lang.get('form.button.close')"
                @click.native="showGoogleMapDialog = false"
              >
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-card-title>

            <v-card-text>
              <google-map :location="googleMap" />
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-row>

      <v-row justify="center">
        <v-dialog
          v-model="showGoogleFenceDialog"
          max-width="800"
        >
          <v-card class="google-map-dialog">
            <v-card-title class="justify-between bg-blue-500 text-white">
              <span class="headline_googlemap">{{ farm_fence.label }}</span>
              <v-btn
                class="close-dialog-icon"
                icon
                :title="lang.get('form.button.close')"
                @click.native="showGoogleFenceDialog = false"
              >
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-card-title>

            <v-card-text>
              <google-fence v-model="fenceCoordinatesComp" :location="fenceCoordinates"
                            :location-center="fenceCoordinatesCenter" :fence-color="farmFenceCoordinateColor"
                            :farm="farm_fence.farm"
                            @saveFence="saveFence"
              />
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-row>
    </template>
  </div>
</template>

<script>
import moment from 'moment'
import Vue from "vue";
import * as VueGoogleMaps from "vue2-google-maps";

export default {
  name: 'FarmFenceForm',
  remember: 'form',

  components: {
    ApiDatatable: () => import('@/Shared/ApiDatatable'),
    SelectInput: () => import('@/Common/Form/SelectInput'),
    TextAreaInput: () => import('@/Common/Form/TextAreaInput'),
    TextInput: () => import('@/Common/Form/TextInput'),
    TrashedMessage: () => import('@/Shared/TrashedMessage'),
    ApiSelect: () => import('@/Common/Form/ApiSelect'),
    GoogleMap: () => import('@/Shared/GoogleMap'),
    GoogleFence: () => import('@/Shared/GoogleFence'),
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
      fenceData: {
          latitude: '',
          longitude: '',
          farm_fence_id: this.farm_fence.id
      },
      googleMap: {

      },
    farmFenceCoordinateId: null,
    farmFenceCoordinateColor: null,
    farmFenceCoordinateColorError: null,
    fenceCoordinates:[

    ],
    fenceCoordinatesComp:[

    ],
    fenceCoordinatesCenter: {

    },
      detailErrors: {},
      photoPreview: null,
      loadingSaveDetail: false,
      isShowAddColorModal: false,
      isShowDeleteFenceModal: false,
      showGoogleMapDialog: false,
      showGoogleFenceDialog: false,
    }
  },
  computed: {
    coordinateHeaders() {
      return [
          { text: this.lang.get('form.heading.id'), value: 'id', sortable: false },
          { text: this.lang.get('form.center_point'), value: 'center_point', sortable: false },
          { text: this.lang.get('form.fence_color'), value: 'fence_color', sortable: false },
          // { text: this.lang.get('form.fence_coordinates'), value: 'fence_coordinates', sortable: false },
          { text: this.lang.get('form.heading.updated_by'), value: 'updated_by_name', sortable: false },
          { text: this.lang.get('form.heading.created_at'), value: 'created_at', sortable: false },
          { text: this.lang.get('form.heading.action'), value: 'farm_fence_coords_actions', sortable: false },
      ]
    },

    status() {
      return this.$store.getters['common/farmGoogleStatus']
    },

    profileError() {
      let locationFields = [
        'latitude',
        'longitude',
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

        _this.$store.dispatch('farmFence/restore', _this.farm_fence.id)
          .then(() => {
            _this.farm_fence.deleted_at = null
          })
          .catch(() => {})
      }
    },
    handleGoogleFenceAdd(item) {
      this.detailErrors = {}
      this.isShowAddFenceModal = true
      if(item){
          this.fenceData = item
      }
      else{
          this.fenceData = {
              latitude: '',
              longitude: '',
          }
      }
      this.fenceData.farm_fence_id = this.farm_fence.id
    },
    openDeleteFenceItem(item){
      this.farmFenceCoordinateId = item.id
      this.isShowDeleteFenceModal = true
    },
    deleteFenceDetail(){
      if (!this.farmFenceCoordinateId) return

      let _this = this

      _this.loadingSaveDetail = true
      _this.detailErrors = {}

      _this
          .$store
          .dispatch('farmFence/deleteCoords', _this.farmFenceCoordinateId)
          .then(() => {
              _this.loadingSaveDetail = false

              _this.isShowDeleteFenceModal = false
              _this.$refs.coordinateTable.getDataFromApi()
          })
          .catch(err => {
              _this.loadingSaveDetail = false

              if (err.response && err.response.data && err.response.data.errors) {
                  _this.detailErrors = err.response.data.errors
              }
          })
    },
    restoreFenceItem(item){
      let _this = this
      _this.loadingSaveDetail = true
      _this.detailErrors = {}

      this
          .$store
          .dispatch('farmFence/restoreCoords', item.id)
          .then(() => {
              _this.loadingSaveDetail = false

              _this.isShowDeleteFenceModal = false
              _this.$refs.coordinateTable.getDataFromApi()
          })
          .catch(err => {
              _this.loadingSaveDetail = false

              if (err.response && err.response.data && err.response.data.errors) {
                  _this.detailErrors = err.response.data.errors
              }
          })
    },
    saveFenceDetail() {
      if (!this.fenceData) return
      let _this = this

      _this.loadingSaveDetail = true
      _this.detailErrors = {}

      let url = _this.fenceData.id ? 'farmFence/updateCoords' : 'farmFence/createCoords'
      _this
          .$store
          .dispatch(url, this.fenceData)
          .then(() => {
              _this.loadingSaveDetail = false

              _this.isShowAddFenceModal = false
              _this.$refs.coordinateTable.getDataFromApi()
          })
          .catch(err => {
              _this.loadingSaveDetail = false

              if (err.response && err.response.data && err.response.data.errors) {
                  _this.detailErrors = err.response.data.errors
              }
          })
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
    showGoogle(item) {
      this.loadingSaveDetail = true
      this.googleMap.lat = item.latitude
      this.googleMap.lng = item.longitude
      this.googleMap.farm_name = this.farm_fence.label
      this.googleMap.farm_address = this.farm_fence.farm.address
      this.googleMapAddress = this.farm_fence.farm.address
      this.showGoogleMapDialog = true
      Vue.use(VueGoogleMaps, {
          load: {
              key: this.farm_fence.farm.api_key,
              libraries: "places" //necessary for places input
          }
      });
      this.loadingSaveDetail = false
    },
    openAddColorModal(item){
        this.farmFenceCoordinateId = item.id
        this.isShowAddColorModal = true
        this.farmFenceCoordinateColor = item.fence_color
    },
    adjustFarmFenceEdit(items){
      this.adjustFarmFence(items, items.id)
    },
    adjustFarmFence(items, farmFenceCoordinateId) {
        this.loadingSaveDetail = true
        if(items == null){
            farmFenceCoordinateId = null
        }
        this.farmFenceCoordinateId = farmFenceCoordinateId

        if(items == null){
            this.fenceCoordinates = []
            this.fenceCoordinatesComp = []
            this.fenceCoordinatesCenter = { lat: this.farm_fence.farm.lat, lng: this.farm_fence.farm.lng }
            this.farmFenceCoordinateColor = '#837083'
        }
        else{
            this.fenceCoordinates = items.fence_coordinates
            this.fenceCoordinatesComp = items.fence_coordinates
            this.fenceCoordinatesCenter = { lat: items.center_point.lat, lng: items.center_point.lng }
            this.farmFenceCoordinateColor = items.fence_color
        }
        if(this.fenceCoordinates && this.fenceCoordinates.length >= 0){
            this.showGoogleFenceDialog = true;
            Vue.use(VueGoogleMaps, {
                load: {
                    key: this.farm_fence.farm.api_key,
                    libraries: "places" //necessary for places input
                }
            });
        }
        this.loadingSaveDetail = false
    },
    saveFenceCoordinateColor: function (){
        if (!this.farmFenceCoordinateId || !this.farmFenceCoordinateColor) return
        let _this = this
        _this.loadingSaveDetail = true
        _this.farmFenceCoordinateColorError = {}
        let formData = new FormData()
        formData.append('fence_color', this.farmFenceCoordinateColor)

        if (_this.farmFenceCoordinateId) {
            formData.append('id', _this.farmFenceCoordinateId)
            formData.append('_method', 'PUT')
        }

        let url = 'farmFence/updateCoordsColor'
        _this
            .$store
            .dispatch(url, formData)
            .then(() => {
                _this.loadingSaveDetail = false

                _this.isShowAddColorModal = false
                _this.$refs.coordinateTable.getDataFromApi()
            })
            .catch(err => {
                _this.loadingSaveDetail = false

                if (err.response && err.response.data && err.response.data.errors) {
                    _this.farmFenceCoordinateColorError = err.response.data.errors
                }
            })
    },
    saveFence: function (fenceCoordinatesComp, center){
      if (!fenceCoordinatesComp || fenceCoordinatesComp.length == 0) return
      let _this = this
      _this.loadingSaveDetail = true
      _this.detailErrors = {}

      if(fenceCoordinatesComp.length == 1){
          fenceCoordinatesComp = fenceCoordinatesComp[0]
      }
      let formData = new FormData()
        formData.append('places', JSON.stringify(fenceCoordinatesComp))
        formData.append('center', JSON.stringify({lat: center.lat, lng: center.lng}))
        formData.append('farm_fence_id', _this.farm_fence.id)

      if (_this.farmFenceCoordinateId) {
          formData.append('id', _this.farmFenceCoordinateId)
          formData.append('_method', 'PUT')
      }

      let url = _this.farmFenceCoordinateId ? 'farmFence/updateCoords' : 'farmFence/createCoords'
      _this
          .$store
          .dispatch(url, formData)
          .then(() => {
              _this.loadingSaveDetail = false

              _this.showGoogleFenceDialog = false
              _this.$refs.coordinateTable.getDataFromApi()
          })
          .catch(err => {
              _this.loadingSaveDetail = false

              if (err.response && err.response.data && err.response.data.errors) {
                  _this.detailErrors = err.response.data.errors
              }
          })

    },

  },
}
</script>

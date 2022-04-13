<template>
  <v-app>

    <div class="card mt-4">
      <div class="card-body">
        <ApiDatatable
          ref="farmFenceTable"
          :headers="headers"
          :show-status="false"
          filter-flex="flex-end"
          :avatar="true"
          :heading="lang.get('menu.farm_fence')"
          :url="`super_admin/farm_fence`"
          :show-add="true"
          :show-add-model="false"
          :edit-route="`${authUser.role}.farm_fence.edit`"
          :create-route="`${authUser.role}.farm_fence.create`"
          @showGoogle="showGoogle"
        />
      </div>

    </div>

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
                          @click.native="showGoogleMapDialog = false"
                          :title="lang.get('form.button.close')"
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
  </v-app>
</template>

<script>
import Vue from "vue";
import * as VueGoogleMaps from "vue2-google-maps";

export default {
  metaInfo: { title: window.lang.get('menu.farm_fence') },

  components: {
    ApiDatatable: () => import('@/Shared/ApiDatatable'),
    TextInput: () => import('@/Common/Form/TextInput'),
    TrashedMessage: () => import('@/Shared/TrashedMessage'),
    ApiSelect: () => import('@/Common/Form/ApiSelect'),
    GoogleMap: () => import('@/Shared/GoogleMap'),
  },

  data() {
    return {
      lang: lang,
      isShowAddFenceModal: false,
      isShowDeleteFenceModal: false,
      loadingSaveDetail: false,
      detailErrors: {},
      fenceData: {},
      googleMap: {
          farm_name: '',
          farm_address: '',
          lat: '',
          lng: ''
      },
      googleMapAddress: '',
      showGoogleMapDialog:false
    }
  },

  computed: {

    headers() {
      return [
        { text: this.lang.get('form.heading.id'), value: 'id' },
        { text: this.lang.get('menu.farms'), value: 'farm.name' },
        { text: this.lang.get('form.label'), value: 'label' },
        { text: this.lang.get('form.description'), value: 'description' },
      ]
    },
  },
    methods: {
        handleGoogleFenceAdd(item) {
            this.isShowAddFenceModal = true
            if(item){
                this.fenceData = item
            }
        },
        openDeleteFenceItem(item){
            this.fenceData = item
            this.isShowDeleteFenceModal = true
        },
        deleteFenceDetail(){
            if (!this.fenceData) return

            let _this = this

            _this.loadingSaveDetail = true
            _this.detailErrors = {}

            _this
                .$store
                .dispatch('farmFence/delete', _this.fenceData.id)
                .then(() => {
                    _this.loadingSaveDetail = false

                    _this.isShowDeleteFenceModal = false
                    _this.$refs.farmFenceTable.getDataFromApi()
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
                .dispatch('farmFence/restore', item.id)
                .then(() => {
                    _this.loadingSaveDetail = false

                    _this.isShowDeleteFenceModal = false
                    _this.$refs.farmFenceTable.getDataFromApi()
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

            let url = _this.fenceData.id ? 'farmFence/update' : 'farmFence/create'

            _this
                .$store
                .dispatch(url, this.fenceData)
                .then(() => {
                    _this.loadingSaveDetail = false

                    _this.isShowAddFenceModal = false
                    _this.$refs.farmFenceTable.getDataFromApi()
                })
                .catch(err => {
                    _this.loadingSaveDetail = false

                    if (err.response && err.response.data && err.response.data.errors) {
                        _this.detailErrors = err.response.data.errors
                    }
                })
        },
        showGoogle(item) {
            this.googleMap.lat = item.latitude
            this.googleMap.lng = item.longitude
            this.googleMap.farm_name = item.farm.name
            this.googleMap.farm_address = item.farm.address
            this.googleMapAddress = item.farm.address
            this.showGoogleMapDialog = true
            Vue.use(VueGoogleMaps, {
                load: {
                    key: item.farm.api_key,
                    libraries: "places" //necessary for places input
                }
            });
        },
    },
}
</script>

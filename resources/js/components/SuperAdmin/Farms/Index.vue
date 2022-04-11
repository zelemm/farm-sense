<template>
  <v-app>

    <div class="card mt-4">
      <div class="card-body">
        <ApiDatatable
          ref="farmTable"
          :headers="headers"
          :show-status="false"
          filter-flex="flex-end"
          :heading="lang.get('menu.farms')"
          :avatar="true"
          :url="`super_admin/farms`"
          :edit-route="`${authUser.role}.farms.edit`"
          :create-route="`${authUser.role}.farms.create`"
          @linkGoogle="linkGoogle"
          @showGoogle="showGoogle"
        />
      </div>

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

    </div>
  </v-app>
</template>

<script>
import Vue from "vue";
import * as VueGoogleMaps from "vue2-google-maps";

export default {
  metaInfo: { title: window.lang.get('menu.farms') },

  components: {
    ApiDatatable: () => import('@/Shared/ApiDatatable'),
    GoogleMap: () => import('@/Shared/GoogleMap'),
  },

  data() {
    return {
      lang: lang,
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
        { text: this.lang.get('form.name'), value: 'name' },
        { text: this.lang.get('form.address'), value: 'address' },
        { text: this.lang.get('form.phone'), value: 'phone' },
        { text: this.lang.get('form.google_map'), value: 'google_map', sortable: false},
        { text: this.lang.get('form.heading.status'), value: 'status' },
        { text: this.lang.get('form.heading.action'), value: 'farm_actions', sortable: false },
      ]
    },
  },
    methods: {
        linkGoogle(item) {
            let _this = this
            _this.sending = true
            _this.$store.dispatch('farms/linkGoogle', item.id)
                .then(res => {
                    _this.sending = false
                    if (_this.$refs.farmTable) {
                        _this.$refs.farmTable.getDataFromApi()
                    }
                })
                .catch(() => {
                    _this.sending = false
                })
        },
        showGoogle(item) {
            this.googleMap.lat = item.lat
            this.googleMap.lng = item.lng
            this.googleMap.farm_name = item.name
            this.googleMap.farm_address = item.address
            this.showGoogleMapDialog = true
            this.googleMapAddress = item.address
            Vue.use(VueGoogleMaps, {
                load: {
                    key: item.api_key,
                    libraries: "places" //necessary for places input
                }
            });
        },
    },
}
</script>

<style lang="scss">
div#google{
    width: 100%;
    height: 400px;
}
.google-map-dialog {
    .subtitle-text-link {
        color: rgba(0, 0, 0, 0.8) !important;
        font-size: small;
    }
    .headline_googlemap {
        font-size: 14px;
        max-width: 600px;
        word-break: break-word;
    }
}
</style>

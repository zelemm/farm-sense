<template>
  <v-row>
    <v-col cols="12" md="6">
      <TextInput
        v-model="form.postcode"
        v-mask="['#####-###']"
        :loading="loadingChangePostcode"
        :label="lang.get('form.postcode') + ' *'"
        type="tel"
        :errors="errors && errors.postcode"
      />
    </v-col>

    <v-col cols="12" md="6">
      <TextInput
        v-model="form.address_line1"
        :label="lang.get('form.address_line1') + ' *'"
        :errors="errors && errors.address_line1"
      />
    </v-col>

    <v-col cols="12" md="6">
      <TextInput
        v-model="form.address_line2"
        :label="lang.get('form.address_line2')"
        :errors="errors && errors.address_line2"
      />
    </v-col>

    <v-col cols="12" md="6">
      <TextInput
        v-model="form.neighbourhood"
        :label="lang.get('form.neighbourhood') + ' *'"
        :errors="errors && errors.neighbourhood"
      />
    </v-col>

    <v-col cols="12" md="4">
      <CountriesSelect v-model="form.country_id" :heading="lang.get('form.country') + ' *'" :errors="errors && errors.country_id" />
    </v-col>

    <v-col cols="12" md="4">
      <Location v-model="form.state_id" :url="stateURl" :heading="lang.get('form.state') + ' *'" :request-params="stateParams" :errors="errors && errors.state_id" />
    </v-col>

    <v-col cols="12" md="4">
      <Location v-model="form.city_id" :url="cityURl" :heading="lang.get('form.city') + ' *'" :request-params="cityParams" :errors="errors && errors.city_id" />
    </v-col>
  </v-row>
</template>

<script>
import axios from 'axios'
import throttle from 'lodash/throttle'

export default {
  components: {
    CountriesSelect: () => import('@/Common/Form/CountriesSelect'),
    Location: () => import('@/Shared/Location'),
    TextInput: () => import('@/Common/Form/TextInput'),
  },

  props: {
    form: Object,
    errors: Object,
  },

  data() {
    return {
      lang: window.lang,
      defaultCountry: window.default_country,
      cityParams: '',
      stateParams: '',
      stateURl: '',
      cityURl: '',
      loadingChangePostcode: false,
    }
  },

  watch: {
    'form.country_id': {
      handler: function (newVal, oldVal) {
        if (newVal != oldVal) {
          this.countryChange(newVal)
        }
      },
      deep: true,
    },

    'form.state_id': {
      handler: function (newVal, oldVal) {
        if (newVal != oldVal) {
          this.stateChange(newVal)
        }
      },
      deep: true,
    },

    'form.postcode': {
      handler: throttle(function (newVal, oldVal) {
        if (newVal != oldVal) {
          this.postcodeChange(newVal)
        }
      }, 300),
      deep: true,
    },
  },

  mounted() {
    // get list states
    if (!this.form.country_id) {
      this.form.country_id = this.defaultCountry
    }

    this.countryChange(this.form.country_id)

    // get list cities
    if (this.form.state_id) {
      this.stateChange(this.form.state_id)
    }
  },

  methods: {
    countryChange(id) {
      this.stateURl = 'states'
      this.cityURl = ''
      this.cityParams = ''

      this.stateParams = `country_id=${id}`
    },

    stateChange(id) {
      this.cityURl = 'cities'

      this.cityParams = `state_id=${id}`
    },

    postcodeChange(postcode) {
      let match = postcode ? postcode.match(/^\d{5}-\d{3}$/) : null

      if (match == null) return

      let _this = this
      _this.loadingChangePostcode = true

      let params = {
        postcode: postcode,
      }

      axios.get('/locations/postcode/get-data', {
        params: params,
      })
        .then((res) => {
          _this.loadingChangePostcode = false

          if (res && res.data && res.data.data && res.data.data.datas) {
            const data = res.data.data.datas

            if (data.state_id && data.state_id == _this.form.state_id) {
              _this.stateChange(data.state_id)
            }

            _this.form.city_id = data.city_id || _this.form.city_id
            _this.form.state_id = data.state_id || _this.form.state_id
            _this.form.country_id = data.country_id || _this.form.country_id
            _this.form.address_line1 = data.address || _this.form.address_line1
            _this.form.address_line2 = data.aid_address || _this.form.address_line2
            _this.form.neighbourhood = data.neighbourhood || _this.form.neighbourhood
          }
        })
        .catch((err) => {
          _this.loadingChangePostcode = false
        })
    },
  },
}
</script>

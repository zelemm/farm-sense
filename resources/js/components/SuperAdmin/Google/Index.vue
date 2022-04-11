<template>
  <v-app>

    <div class="card mt-4">
      <div class="card-body">
        <ApiDatatable
          ref="farmGoogleTable"
          :headers="headers"
          :show-status="false"
          filter-flex="flex-end"
          :heading="lang.get('menu.farm_google')"
          :avatar="true"
          :url="`super_admin/farm_google`"
          :edit-route="`${authUser.role}.farm_google.edit`"
          :create-route="`${authUser.role}.farm_google.create`"
          @handleGoogleAuth="handleGoogleAuth"
        />
      </div>

    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('menu.farm_google') },

  components: {
    ApiDatatable: () => import('@/Shared/ApiDatatable'),
  },

  data() {
    return {
      lang: lang,
    }
  },

  computed: {

    headers() {
      return [
        { text: this.lang.get('form.heading.id'), value: 'id' },
        { text: this.lang.get('menu.farms'), value: 'farm.name' },
        { text: this.lang.get('form.label'), value: 'label' },
        { text: this.lang.get('form.organisation_id'), value: 'organisation_id' },
        { text: this.lang.get('form.client_id'), value: 'client_id' },
        { text: this.lang.get('form.timezone'), value: 'timezone' },
        { text: this.lang.get('form.heading.status'), value: 'status' },
        { text: this.lang.get('form.heading.action'), value: 'farm_google_actions' },
      ]
    },
  },
    methods: {
        handleGoogleAuth(item) {
            let _this = this
            _this.sending = true
            _this.$store.dispatch('farmGoogle/auth', item.id)
                .then(res => {
                    _this.sending = false
                    // console.log(res.data.data.auth_redirect)
                    window.location.href = res.data.data.auth_redirect

                    if (_this.$refs.farmGoogleTable) {
                        _this.$refs.farmGoogleTable.getDataFromApi()
                    }
                })
                .catch(() => {
                    _this.sending = false
                })
        },
    },
}
</script>

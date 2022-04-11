<script src="../../../store/modules/farm-google-module.js"></script>
<template>
  <v-app>

    <div class="card mt-4">
      <div class="card-body">
        <ApiDatatable
          :headers="headers"
          :show-status="false"
          filter-flex="flex-end"
          :heading="lang.get('menu.farm_google')"
          :avatar="true"
          :url="`super_admin/farm_google`"
          :edit-route="`${authUser.role}.farm_google.edit`"
          :create-route="`${authUser.role}.farm_google.create`"
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
        { text: this.lang.get('form.client_secret'), value: 'client_secret' },
        { text: this.lang.get('form.timezone'), value: 'timezone' },
        { text: this.lang.get('form.google_scopes'), value: 'google_scopes' },
        { text: this.lang.get('form.code'), value: 'code' },
        { text: this.lang.get('form.access_token'), value: 'access_token' },
        { text: this.lang.get('form.refresh_token'), value: 'father.refresh_token' },
        { text: this.lang.get('form.scope'), value: 'mother.scope' },
        { text: this.lang.get('form.expires_in'), value: 'expires_in' },
        { text: this.lang.get('form.heading.status'), value: 'status' },
        { text: lang.get('form.heading.actions'), value: 'farm_google_actions' },
      ]
    },
  },
}
</script>

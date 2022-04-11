<template>
  <v-app>
    <div>
      <FarmGoogleForm :farm_google="farm_google" />
    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('form.farm_google_lang.create') },

  components: {
    FarmGoogleForm: () => import('./Form'),
  },

 data() {
    return {
      farm_google: {
          status: 'Active',
          timezone: 'Australia/Sydney',
      }
    }
  },

  computed: {
  },
}
</script>

<template>
  <v-app>
    <template v-if="!loadingFarmGoogle">
      <FarmGoogleForm :farm_google="farm_google" />
    </template>
    <div class="card" v-else>
      <div class="card-body">
        <div class="p-5 text-center w-full">
          <i class="mdi mdi-36px mdi-spin mdi-loading" />
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('form.farm_google_lang.edit') },

  components: {
    FarmGoogleForm: () => import('./Form'),
  },

  data() {
    return {
      farm_google: {},
      loadingFarmGoogle: false,
    }
  },

  computed: {

  },

  mounted() {
      this.getFarmGoogle();
  },

  methods: {
      getFarmGoogle() {
          this.loadingFarmGoogle = true
          this.$store.dispatch('farmGoogle/find', this.$route.params.id)
              .then(data => {
                  this.loadingFarmGoogle = false
                  this.farm_google = data
              })
              .catch(() => {
                  this.loadingFarmGoogle = false
                  this.$router.push({ name: `${this.authUser.role}.farm_google` })
              })
      },
  },
}
</script>

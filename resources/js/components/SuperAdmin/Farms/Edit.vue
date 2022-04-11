<template>
  <v-app>
    <template v-if="!loadingFarm">
      <FarmForm :farm="farm" />
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
  metaInfo: { title: window.lang.get('form.farm_lang.edit') },

  components: {
    FarmForm: () => import('./Form'),
  },

  data() {
    return {
      farm: {},
      loadingFarm: false,
    }
  },

  computed: {

  },

  mounted() {
      this.getFarm();
  },

  methods: {
      getFarm() {
          this.loadingFarm = true
          this.$store.dispatch('farms/find', this.$route.params.id)
              .then(data => {
                  this.loadingFarm = false
                  this.farm = data
              })
              .catch(() => {
                  this.loadingFarm = false
                  this.$router.push({ name: `${this.authUser.role}.farms` })
              })
      },
  },
}
</script>

<template>
  <v-app>
    <template v-if="!loadingFarmFence">
      <FarmFenceForm :farm_fence="farm_fence" />
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
  metaInfo: { title: window.lang.get('form.farm_fence_lang.edit') },

  components: {
    FarmFenceForm: () => import('./Form'),
  },

  data() {
    return {
      farm_fence: {},
      loadingFarmFence: false,
    }
  },

  computed: {

  },

  mounted() {
      this.getFarmFence();
  },

  methods: {
      getFarmFence() {
          this.loadingFarmFence = true
          this.$store.dispatch('farmFence/find', this.$route.params.id)
              .then(data => {
                  this.loadingFarmFence = false
                  this.farm_fence = data
              })
              .catch(() => {
                  this.loadingFarmFence = false
                  this.$router.push({ name: `${this.authUser.role}.farm_fence` })
              })
      },
  },
}
</script>

<template>
  <v-app>
    <template v-if="!loadingCattle">
      <CattleForm :cattle="cattle" />
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
  metaInfo: { title: window.lang.get('form.cattle_lang.edit') },

  components: {
    CattleForm: () => import('./Form'),
  },

  data() {
    return {
      cattle: {},
      loadingCattle: false,
    }
  },

  computed: {

  },

  mounted() {
      this.getCattle();
  },

  methods: {
      getCattle() {
          this.loadingCattle = true
          this.$store.dispatch('cattle/find', this.$route.params.id)
              .then(data => {
                  this.loadingCattle = false
                  this.cattle = data
              })
              .catch(() => {
                  this.loadingCattle = false
                  this.$router.push({ name: `${this.authUser.role}.cattle` })
              })
      },
  },
}
</script>

<template>
  <v-app>
    <div>
      <FarmForm :farm="farm" />
    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('form.farm_lang.create') },

  components: {
    FarmForm: () => import('./Form'),
  },

 data() {
    return {
      farm: {
          status: 'Active'
      }
    }
  },

  computed: {
  },
}
</script>

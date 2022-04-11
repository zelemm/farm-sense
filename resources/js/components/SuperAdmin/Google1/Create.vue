<template>
  <v-app>
    <div>
      <CattleForm :cattle="cattle" />
    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('form.cattle_lang.create') },

  components: {
    CattleForm: () => import('./Form'),
  },

 data() {
    return {
      cattle: {
          breed: 'Angus',
          cattle_type: 'Cow'
      }
    }
  },

  computed: {
  },
}
</script>

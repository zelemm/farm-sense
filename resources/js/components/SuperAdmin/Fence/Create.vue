<template>
  <v-app>
    <div>
      <FarmFenceForm :farm_fence="farm_fence" />
    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('form.farm_fence_lang.create') },

  components: {
    FarmFenceForm: () => import('./Form'),
  },

 data() {
    return {
      farm_fence: {
      }
    }
  },

  computed: {
  },
}
</script>

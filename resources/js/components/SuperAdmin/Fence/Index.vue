<template>
  <v-app>

    <div class="card mt-4">
      <div class="card-body">
        <ApiDatatable
          ref="farmFenceTable"
          :headers="headers"
          :show-status="false"
          filter-flex="flex-end"
          :avatar="true"
          :heading="lang.get('menu.farm_fence')"
          :url="`super_admin/farm_fence`"
          :show-add="true"
          :show-add-model="false"
          :edit-route="`${authUser.role}.farm_fence.edit`"
          :create-route="`${authUser.role}.farm_fence.create`"
        />
      </div>

    </div>

  </v-app>
</template>

<script>
import Vue from "vue";
import * as VueGoogleMaps from "vue2-google-maps";

export default {
  metaInfo: { title: window.lang.get('menu.farm_fence') },

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
        { text: this.lang.get('form.description'), value: 'description' },
      ]
    },
  },
    methods: {
    },
}
</script>

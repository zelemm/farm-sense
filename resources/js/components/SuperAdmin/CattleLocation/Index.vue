<template>
  <v-app>

    <div class="card mt-4">
      <div class="card-body">
        <ApiDatatable
          :headers="headers"
          :show-status="false"
          filter-flex="flex-end"
          :heading="lang.get('menu.cattle_location')"
          :avatar="true"
          :url="`super_admin/cattle_location`"
        />
      </div>
    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('menu.cattle_location') },

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
        { text: this.lang.get('menu.cattle'), value: 'cattle.name' },
        { text: this.lang.get('form.latitude'), value: 'latitude' },
        { text: this.lang.get('form.longitude'), value: 'longitude' },
        { text: this.lang.get('form.green_zone'), value: 'green_zone' },
        { text: this.lang.get('form.purple_zone'), value: 'purple_zone' },
        { text: this.lang.get('form.orange_zone'), value: 'orange_zone' },
        { text: this.lang.get('form.red_zone'), value: 'red_zone' },
        { text: this.lang.get('form.grazing'), value: 'grazing' },
        { text: this.lang.get('form.standing'), value: 'standing' },
        { text: this.lang.get('form.sitting'), value: 'sitting' },
        { text: this.lang.get('form.location_date'), value: 'location_date' },
      ]
    },
  },
}
</script>

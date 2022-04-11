<template>
  <v-app>

    <div class="card mt-4">
      <div class="card-body">
        <ApiDatatable
          :headers="headers"
          :show-status="false"
          filter-flex="flex-end"
          :heading="lang.get('menu.cattle')"
          :avatar="true"
          :url="`super_admin/cattle`"
          :edit-route="`${authUser.role}.cattle.edit`"
          :create-route="`${authUser.role}.cattle.create`"
        />
      </div>
    </div>
  </v-app>
</template>

<script>
export default {
  metaInfo: { title: window.lang.get('menu.cattle') },

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
        { text: this.lang.get('form.breed'), value: 'breed' },
        { text: this.lang.get('form.mac_id'), value: 'mac_id' },
        { text: this.lang.get('form.arrival'), value: 'arrival' },
        { text: this.lang.get('form.name'), value: 'name' },
        { text: this.lang.get('form.date_of_birth'), value: 'date_of_birth' },
        { text: this.lang.get('form.date_of_death'), value: 'date_of_death' },
        { text: this.lang.get('form.sex'), value: 'sex' },
        { text: this.lang.get('form.weight'), value: 'weight' },
        { text: this.lang.get('form.father_cow'), value: 'father.name' },
        { text: this.lang.get('form.mother_cow'), value: 'mother.name' },
        { text: this.lang.get('form.heading.status'), value: 'status' },
      ]
    },
  },
}
</script>

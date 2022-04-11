<template>
  <v-sheet height="600">
    <v-toolbar flat height="auto" class="calendar-toolbar flex-wrap">
      <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
        {{ 'form.filter.today' | trans }}
      </v-btn>
      <v-btn fab text small color="grey darken-2" @click="prev">
        <v-icon small> mdi-chevron-left</v-icon>
      </v-btn>
      <v-btn fab text small color="grey darken-2" @click="next">
        <v-icon small>mdi-chevron-right</v-icon>
      </v-btn>
      <v-toolbar-title v-if="$refs.calendar">
        {{ $refs.calendar.title }}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      
      <v-select v-if="customerFilter" v-model="form.customer" class="mr-2" :items="customers" item-text="name" item-value="id" :label="lang.get('form.filter.customers')" hide-details clearable />
      <v-select v-if="serviceFilter" v-model="form.service" class="mr-2" :items="services" item-text="name" item-value="id" :label="lang.get('form.filter.services')" hide-details clearable />
      <v-select
        v-if="servicesFilter"
        v-model="form.services"
        class="mr-2"
        :items="services"
        item-text="name"
        item-value="id"
        :label="lang.get('form.filter.services')"
        :no-data-text="lang.get('common.data_table.no_data')"
        multiple
        hide-details
        clearable
      />
      <v-select v-if="staffFilter" v-model="form.emp" class="mr-2" :items="employees" item-text="name" item-value="id" :label="lang.get('form.filter.staffs')" hide-details clearable />
      <v-select
        v-if="staffsFilter"
        v-model="form.staffs"
        class="mr-2"
        :items="employees"
        item-text="name"
        item-value="id"
        :label="lang.get('form.filter.staffs')"
        :no-data-text="lang.get('common.data_table.no_data')"
        multiple
        hide-details
        clearable
      />
      <v-select v-if="locationFilter" v-model="form.location" class="mr-2" :items="locations" item-text="name" item-value="id" :label="lang.get('form.filter.locations')" hide-details clearable />
      <v-select
        v-if="locationsFilter"
        v-model="form.locations"
        class="mr-2"
        :items="locations"
        item-text="name"
        item-value="id"
        :label="lang.get('form.filter.locations')"
        :no-data-text="lang.get('common.data_table.no_data')"
        multiple
        hide-details
        clearable
      />

      <v-menu bottom right v-if="type=='day'">
        <template v-slot:activator="{ on, attrs }">
          <v-btn outlined color="grey darken-2" class="mr-3" @click="type = 'month'">
            <span>{{ 'form.filter.reset_view' | trans }}</span>
          </v-btn>
        </template>
      </v-menu>
      <v-menu bottom right>
        <template v-slot:activator="{ on, attrs }">
          <v-btn outlined color="grey darken-2" @click="resetFilters">
            <span>{{ 'form.filter.reset_filters' | trans }}</span>
          </v-btn>
        </template>
      </v-menu>
    </v-toolbar>
    <v-calendar
      ref="calendar"
      v-model="focus"
      :events="datasEvents"
      :type="type"
      :event-overlap-threshold="30"
      :event-color="getEventColor"
      @click:more="viewDay"
      @click:date="viewDay"
      @click:event="goToView"
      @change="updateRange"
    ></v-calendar>
  </v-sheet>
</template>

<script>
import axios from 'axios'
import throttle from 'lodash/throttle'

export default {
  props: {
    url: {
      type: [String, null]
    },
    viewRoute: {
      type: [String, null]
    },
    customerFilter: {
      type: Boolean,
      default: false,
    },
    serviceFilter: {
      type: Boolean,
      default: false,
    },
    staffFilter: {
      type: Boolean,
      default: false,
    },
    locationFilter: {
      type: Boolean,
      default: false,
    },
    servicesFilter: {
      type: Boolean,
      default: false,
    },
    staffsFilter: {
      type: Boolean,
      default: false,
    },
    locationsFilter: {
      type: Boolean,
      default: false,
    },
    customers: {
      type: Array,
    },
    services: {
      type: Array,
    },
    employees: {
      type: Array,
    },
    locations: {
      type: Array,
    },
  },

  data() {
    return {
      lang: lang,
      params: '',
      form: {
        customer: '',
        service: '',
        emp: '',
        location: '',
        services: '',
        staffs: '',
        locations: '',
      },
      type: 'month',
      focus: '',
      datas:[],
      loading: false,
      startDate: '',
      endDate: '',
      datasEvents: [],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
    }
  },

  computed: {
    authUser() {
      return this.$store.getters['auth/user']
    },
  },

  watch: {
    form: {
      handler: throttle(function() {
        this.params = ''

        for (let i in this.form) {
          if (this.form[i]) {
            this.params += `&${i}=${this.form[i]}`
          }
        }

        var app = this;

        setTimeout(function(){
          app.updateCalender();
        }, 200);
      }, 200),
      deep: true,
    },
  },

  methods: {
    resetFilters () {
      if(this.params) {
        this.form.customer = ''
        this.form.service = ''
        this.form.emp = ''
        this.form.location = ''
        this.form.services = ''
        this.form.staffs = ''
        this.form.locations = ''

        this.getAllEvents()
      }
    },
    setToday () {
      this.focus = ''
    },
    prev () {
      this.$refs.calendar.prev()
    },
    next () {
      this.$refs.calendar.next()
    },
    viewDay ({ date }) {
      this.focus = date
      this.type = 'day'
    },
    goToView ({ event }) {
      if(this.viewRoute) {
        let id = event.dataId

        if (this.viewRoute == `${this.authUser.role}.appointments` && event.apptId) {
          id = event.apptId
        }

        this.$router.push({name: `${this.viewRoute}.show`, params: {id: id}});
      }
    },
    updateCalender () {
      var app = this
      const events = []
      this.getAllEvents(app.startDate, app.endDate);
      var myVar = setInterval(() => {
        if(app.loading == false) {
          var datas = this.datas;
          clearInterval(myVar);
          for (const data of datas) {
            let min, max, name, apptId;

            if (this.url == '/exams/calendar-exams') {
              apptId = data.appointment_id
              min = new Date(`${data.exam_date}`)
              max = new Date(`${data.exam_date}`)
              name = `${data.address}/${data.services_name}/${data.nurse}/${data.patient}`
            } else {
              min = new Date(`${data.appointment_date}T${data.start_time}`)
              max = new Date(`${data.appointment_date}T${data.end_time}`)
              name = `${data.address}/${data.service_name}/${data.nurse}/${data.patient}`
            }

            const allDay = app.rnd(0, 3) === 0
            const firstTimestamp = min.getTime()
            const secondTimestamp = max.getTime()
            const first = new Date(firstTimestamp - (firstTimestamp % 900000))
            const second = new Date(secondTimestamp - (secondTimestamp % 900000))
            const dataId = data.id

            events.push({
              name: name,
              color: app.colors[app.rnd(0, app.colors.length - 1)],
              start: first,
              end:second,
              timed: !allDay,
              dataId: dataId,
              apptId: apptId,
            })
          }

          app.datasEvents = events
        }
      }, 1000);
    },
    showEvent ({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event
        this.selectedElement = nativeEvent.target
        setTimeout(() => {
          this.selectedOpen = true
        }, 10)
      }

      if (this.selectedOpen) {
        this.selectedOpen = false
        setTimeout(open, 10)
      } else {
        open()
      }

      nativeEvent.stopPropagation()
    },
    updateRange ({ start, end }) {
      var app = this
      app.startDate = start.date
      app.endDate = end.date

      app.updateCalender();
    },
    getAllEvents (startDate = null, endDate = null) {
      let param = '';
      this.loading = true

      if(startDate && endDate) {
        param = `dates=${startDate},${endDate}`
      }

      if(this.params) {
        param += `${this.params}`;
      }

      axios.get(`${this.url}?${param}`)
        .then((res) => {
          if (res && res.data) {
            this.datas = res.data.data || []
          }
          this.loading = false
        })
        .catch((error) => {
          this.loading = false
        })
    },
    rnd (a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a
    },
    getEventColor (event) {
      return event.color
    },
  },
}
</script>

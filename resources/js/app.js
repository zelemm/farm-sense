import Vue from 'vue'

import JQuery from 'jquery'
window.$ = JQuery

import VueMeta from 'vue-meta'
import axios from 'axios'
import Notifications from 'vue-notification'
import DatetimePicker from 'vuetify-datetime-picker'

import App from './components/App.vue'


window.Lang = require('lang.js')

// get the data source
const translations = require('../assets/js/vue-translations.js')

// this is why we used the --no-lib in our commandline
const lang = window.lang = Lang

lang.setLocale($cookies.get('lang') || user_lang)
// lang.setLocale('en');  // @TODO when we set up language switcher

// 'en' is the default, but if you need this for other languages
// there are fallbacks in Lang.js you can use, as well
if (lang.getLocale() === 'undefined'){
    lang.setLocale('en')
}

// This is our normal Vue filter. '...args' allows us to use any
// number of parameters in our strings
Vue.filter('trans', (...args) => {
    return lang.get(...args)
})


import Vuetify from 'vuetify'
// import { mdiAccount } from '@mdi/js'
// Main JS (in UMD format)
// import VueTimepicker from 'vue2-timepicker'
// import Multiselect  from 'vue-multiselect'
import router from './router/index'
import store from './store/index'
import './config/http'
import VueTheMask from 'vue-the-mask'
import 'vuetify/dist/vuetify.min.css'
// import * as VueGoogleMaps from 'vue2-google-maps'
import VueCookies from 'vue-cookies'

// Import Bootstrap an BootstrapVue CSS files (order is important)

Vue.use(Notifications)
Vue.config.productionTip = false

Vue.use(Vuetify)

Vue.use(DatetimePicker)
Vue.use(VueMeta)
Vue.use(VueCookies)

Vue.use(VueTheMask)

// Vue.use(VueGoogleMaps, {
//     load: {
//         key: mapApiKey,
//     },
// })

Vue.mixin({
    computed: {
        authUser() {
            return this.$store.getters['auth/user']
        },
    },
})

// Set axios default header
axios.defaults.headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
axios.defaults.headers['Content-Type'] = 'application/json'

const app = new Vue({
    router,
    store,
    metaInfo: {
        titleTemplate: (title) => title ? `${title} - Sense Your Farm` : 'Sense Your Farm',
    },
    vuetify: new Vuetify({
        theme: { light: true },
    }),
    render: (h) => h(App),
}).$mount('#app')

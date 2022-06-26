import axios from 'axios'
import Vue from 'vue'

axios.defaults.baseURL = '/api/v1'
axios.defaults.headers.common['Authorization'] =
    'Bearer ' + localStorage.getItem('fs_access_token')

axios.interceptors.response.use(
  (response) => {
    // Show success message
    if (response && response.data && response.data.message) {
      const success = response.data.success

      Vue.notify({
        title: lang.get(success ? 'form.success' : 'form.error'),
        type: success ? 'success' : 'error',
        text: response.data.message,
        duration: 5000,
      })
    }

    return response
  },
  (error) => {
    if (error && error.response && error.response.status === 422) {
      // Show error message
      const { errors } = error.response.data
      const keys = Object.keys(errors)
      const message = errors[keys[0]] ? errors[keys[0]][0] : ''

      if (message)
      {
        Vue.notify({
          title: lang.get('form.error'),
          type: 'error',
          text: message,
          duration: 5000,
        })
      }
    } else {
      // Show error message
      if (error.response && error.response.data && error.response.data.message) {
        Vue.notify({
          title: lang.get('form.error'),
          type: 'error',
          text: error.response.data.message,
          duration: 5000,
        })
      }
    }

    // Catch 401 request
    if (error && error.response && error.response.status === 401) {
    } else {
      return Promise.reject(error)
    }
  }
)

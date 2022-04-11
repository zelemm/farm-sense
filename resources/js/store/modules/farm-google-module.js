import axios from 'axios'

export default  {
  namespaced: true,

  state: {
    all: [],
  },

  mutations: {
    createSuccess(state) {
      state.all = []
    },
  },

  actions: {

    find({commit}, id) {
      return new Promise((resolve, reject) => {
        axios.get(`/super_admin/farm_google/${id}`)
          .then(res => {
            resolve(res.data.data.farm_google)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    create({commit}, itemForm) {
      return new Promise((resolve, reject) => {
        axios
          .post('/super_admin/farm_google', itemForm, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          .then(res => {
            commit('createSuccess')
            resolve(res.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    update({commit}, farmGoogleForm) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/super_admin/farm_google/${farmGoogleForm.get('id')}`, farmGoogleForm, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          .then(res => {
            resolve(res.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    delete({commit}, farmGoogleId) {
      return new Promise((resolve, reject) => {
          axios.delete(`/super_admin/farm_google/${farmGoogleId}`)
              .then((res) => {
                  resolve(res)
              })
              .catch((error) => {
                  reject(error)
              })
      })
    },

    restore({commit}, farmGoogleId) {
      return new Promise((resolve, reject) => {
          axios.put(`/super_admin/farm_google/${farmGoogleId}/restore`)
              .then((res) => {
                  resolve(res)
              })
              .catch((error) => {
                  reject(error)
              })
      })
    },

    auth({commit}, farmGoogleId) {
      return new Promise((resolve, reject) => {
          axios.get(`/super_admin/farm_google/${farmGoogleId}/auth`)
              .then((res) => {
                  resolve(res)
              })
              .catch((error) => {
                  reject(error)
              })
      })
    },

  },

  getters: {
    all: (state) => state.all,
  },
}

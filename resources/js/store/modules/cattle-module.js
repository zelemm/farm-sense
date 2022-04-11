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
        axios.get(`/super_admin/cattle/${id}`)
          .then(res => {
            resolve(res.data.data.cattle)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    create({commit}, itemForm) {
      return new Promise((resolve, reject) => {
        axios
          .post('/super_admin/cattle', itemForm, {
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

    update({commit}, farmForm) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/super_admin/cattle/${farmForm.get('id')}`, farmForm, {
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

    delete({commit}, farmId) {
      return new Promise((resolve, reject) => {
          axios.delete(`/super_admin/cattle/${farmId}`)
              .then((res) => {
                  resolve(res)
              })
              .catch((error) => {
                  reject(error)
              })
      })
    },

    restore({commit}, farmId) {
      return new Promise((resolve, reject) => {
          axios.put(`/super_admin/cattle/${farmId}/restore`)
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

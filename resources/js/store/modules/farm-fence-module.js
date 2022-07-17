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
        axios.get(`/super_admin/farm_fence/${id}`)
          .then(res => {
            resolve(res.data.data.farm_fence)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    create({commit}, itemForm) {
      return new Promise((resolve, reject) => {
        axios
          .post('/super_admin/farm_fence', itemForm, {
            // headers: { 'Content-Type': 'multipart/form-data' },
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

    update({commit}, farmFenceForm) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/super_admin/farm_fence/${farmFenceForm.get('id')}`, farmFenceForm, {
            // headers: { 'Content-Type': 'multipart/form-data' },
          })
          .then(res => {
            resolve(res.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    delete({commit}, farmFenceId) {
      return new Promise((resolve, reject) => {
        axios.delete(`/super_admin/farm_fence/${farmFenceId}`)
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    restore({commit}, farmFenceId) {
      return new Promise((resolve, reject) => {
        axios.put(`/super_admin/farm_fence/${farmFenceId}/restore`)
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    auth({commit}, farmFenceId) {
      return new Promise((resolve, reject) => {
        axios.get(`/super_admin/farm_fence/${farmFenceId}/auth`)
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    findCoords({commit}, id) {
      return new Promise((resolve, reject) => {
        axios.get(`/super_admin/farm_fence/coordinate/${id}`)
          .then(res => {
            resolve(res.data.data.farm_fence)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    createCoords({commit}, itemForm) {
      return new Promise((resolve, reject) => {
        axios
          .post('/super_admin/farm_fence/coordinate', itemForm, {
            // headers: { 'Content-Type': 'multipart/form-data' },
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

    updateCoords({commit}, farmFenceForm) {
      return new Promise((resolve, reject) => {

        axios
          .post(`/super_admin/farm_fence/coordinate/${farmFenceForm.get('id')}`, farmFenceForm, {
            // headers: { 'Content-Type': 'multipart/form-data' },
          })
          .then(res => {
            resolve(res.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    deleteCoords({commit}, farmFenceId) {
      return new Promise((resolve, reject) => {
        axios.delete(`/super_admin/farm_fence/coordinate/${farmFenceId}`)
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    restoreCoords({commit}, farmFenceId) {
      return new Promise((resolve, reject) => {
        axios.put(`/super_admin/farm_fence/coordinate/${farmFenceId}/restore`)
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

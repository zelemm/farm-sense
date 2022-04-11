import axios from 'axios'

export default  {
    namespaced: true,

    state: {},

    mutations: {
        updateProfileSuccess(state, user) {
            this.state.auth.user = user
            if (user && user.customer) {
                this.state.customer.customer = user.customer
            }
            if (user && user.employee) {
                this.state.employee.employee = user.employee
            }
        },
    },

    actions: {
        getRoles({commit}) {
            return new Promise((resolve, reject) => {
                axios.get('/admin/users/get-roles')
                    .then(res => {
                        commit('getRolesSuccess', res.data.data.roles)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        find({commit}, id) {
            return new Promise((resolve, reject) => {
                axios.get(`/admin/users/${id}`)
                    .then((res) => {
                        resolve(res.data.data.user)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },

        create({commit}, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/admin/users', data)
                    .then(res => {
                        resolve(res.data.data.user)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        update({commit}, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/users/${data.get('id')}`, data, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                    })
                    .then(res => {
                        resolve(res.data.data.user)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        updateProfile({commit}, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/users/profile', data, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                    })
                    .then(res => {
                        commit('updateProfileSuccess', res.data.data.user)
                        resolve(res)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        delete({commit}, userId) {
            return new Promise((resolve, reject) => {
                axios.delete(`/admin/users/${userId}`)
                    .then((res) => {
                        resolve(res)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },

        restore({commit}, userId) {
            return new Promise((resolve, reject) => {
                axios.put(`/admin/users/${userId}/restore`)
                    .then((res) => {
                        resolve(res)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },

        getUsersList({commit}, roles) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/users/list?roles=${roles}`)
                    .then(res => {
                        resolve(res.data.data.datas)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
    },

    getters: {},
}

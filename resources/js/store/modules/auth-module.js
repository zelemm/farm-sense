import axios from 'axios'

export default  {
    namespaced: true,

    state: {
        fs_access_token: localStorage.getItem('fs_access_token'),
        user: null,
        token: null,
    },

    mutations: {
        getProfileSuccess(state, user) {
            state.user = user
            if (user && user.farms) {
                this.state.farms.farms = user.farms
            }
        },

        loginSuccess(state, data) {
            state.token = data.token
            localStorage.setItem('fs_access_token', data.token)
            axios.defaults.headers.common['Authorization'] =
                'Bearer ' + data.token
        },

        logoutSuccess(state) {
            state.user = null
            state.fs_access_token = null

            localStorage.removeItem('fs_access_token')
            localStorage.removeItem('pathToLoadAfterLogin')

            delete axios.defaults.headers.common['Authorization']
        },
    },

    actions: {
        getProfile({commit}) {
            return new Promise((resolve, reject) => {
                axios
                    .get('/profile')
                    .then((res) => {
                        commit('getProfileSuccess', res.data.data.user)
                        resolve(res.data.data.user)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },

        login({commit}, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/login', credentials)
                    .then((res) => {
                        commit('loginSuccess', res.data.data)
                        resolve(res)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },

        forgotPassword({commit}, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/passwords/reset', credentials)
                    .then((res) => {
                        resolve(true)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },

        changePassword({commit}, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/passwords/change', credentials)
                    .then((res) => {
                        resolve(res)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },

        logout({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/logout')
                    .then((res) => {
                        commit('logoutSuccess')
                        resolve(res)
                    })
                    .catch((error) => {
                        reject(error)
                    })
            })
        },
    },

    getters: {
        isLoggedIn: (state) => !!state.fs_access_token,
        user: (state) => state.user,
    },
}

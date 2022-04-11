import axios from 'axios'

export default  {
    namespaced: true,

    state: {
        farmStatus: null,
        cattleStatus: null,
        cattleArrivalStatus: null,
        cattleBreed: null,
        cattleSex: null,
        cattleType: null,
        farmGoogleStatus: null,
    },

    mutations: {

        getFarmStatusSuccess(state, farmStatus) {
            state.farmStatus = farmStatus
        },

        getCattleStatusSuccess(state, cattleStatus) {
            state.cattleStatus = cattleStatus
        },

        getCattleArrivalStatusSuccess(state, cattleArrivalStatus) {
            state.cattleArrivalStatus = cattleArrivalStatus
        },

        getCattleBreedSuccess(state, cattleBreed) {
            state.cattleBreed = cattleBreed
        },

        getCattleSexSuccess(state, cattleSex) {
            state.cattleSex = cattleSex
        },

        getCattleTypeSuccess(state, cattleType) {
            state.cattleType = cattleType
        },

        getFarmGoogleStatusSuccess(state, farmGoogleStatus) {
            state.farmGoogleStatus = farmGoogleStatus
        },
    },

    actions: {

        getFarmStatus({commit}) {
            return new Promise((resolve, reject) => {
                axios.get('/common/farmStatus')
                    .then(res => {
                        commit('getFarmStatusSuccess', res.data.data.status)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        getCattleStatus({commit}) {
            return new Promise((resolve, reject) => {
                axios.get('/common/cattleStatus')
                    .then(res => {
                        commit('getCattleStatusSuccess', res.data.data.cattleStatus)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        getCattleArrivalStatus({commit}) {
            return new Promise((resolve, reject) => {
                axios.get('/common/cattleArrivalStatusList')
                    .then(res => {
                        commit('getCattleArrivalStatusSuccess', res.data.data.cattleArrivalStatus)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        getCattleBreed({commit}) {
            return new Promise((resolve, reject) => {
                axios.get('/common/cattleBreed')
                    .then(res => {
                        commit('getCattleBreedSuccess', res.data.data.cattleBreed)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        getCattleSex({commit}) {

            return new Promise((resolve, reject) => {
                axios.get('/common/cattleSex')
                    .then(res => {
                        commit('getCattleSexSuccess', res.data.data.cattleSex)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        getCattleType({commit}) {
            return new Promise((resolve, reject) => {
                axios.get('/common/cattleType')
                    .then(res => {
                        commit('getCattleTypeSuccess', res.data.data.cattleType)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

        getFarmGoogleStatus({commit}) {
            return new Promise((resolve, reject) => {
                axios.get('/common/farmGoogleStatus')
                    .then(res => {
                        commit('getFarmGoogleStatusSuccess', res.data.data.farmGoogleStatus)
                        resolve(true)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },

    },

    getters: {
        farmStatus: (state) => state.farmStatus,
        cattleStatus: (state) => state.cattleStatus,
        cattleArrivalStatus: (state) => state.cattleArrivalStatus,
        cattleBreed: (state) => state.cattleBreed,
        cattleSex: (state) => state.cattleSex,
        cattleType: (state) => state.cattleType,
        farmGoogleStatus: (state) => state.farmGoogleStatus,
    },
}

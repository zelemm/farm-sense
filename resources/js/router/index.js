import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/index'

import authRoutes from './auth-routes'
import superAdminRoutes from './superAdmin-routes'

Vue.use(Router)

const createRouter = () => {
    const routes = [
        ...authRoutes,
        ...superAdminRoutes,
    ]

    const router = new Router({
        mode: 'history',
        routes: routes,
    })

    return router
}
const router = createRouter()

Router.prototype.resetRouter = function() {
    const newRouter = createRouter()
    router.matcher = newRouter.matcher // the relevant part
}

router.beforeEach((to, from, next) => {
    if (store.getters['auth/isLoggedIn'] && !store.state.auth.user) {
        store.dispatch('auth/getProfile')
            .then((user) => {
                if (to && (to.path === '/' || to.path === '/login')) {
                    next({ name: `${user.role}.dashboard` })
                } else {
                    next()
                }
            })
            .catch(() => {
                next()
            })
    } else {
        next()
    }
})

export default router

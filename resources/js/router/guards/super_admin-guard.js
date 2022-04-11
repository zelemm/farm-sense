import store from '../../store/index'

function SuperAdminGuard(to, from, next) {
    let user = store.getters['auth/user']
    if (!user) {
        localStorage.setItem('pathToLoadAfterLogin', to.fullPath)
        next({ name: 'login' })
    } else if(user.role != 'super_admin') {
        return next({ name: `${user.role}.dashboard` })
    }
    else {
        next()
    }
}

export default SuperAdminGuard

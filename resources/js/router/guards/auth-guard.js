import store from '../../store/index'

function AuthGuard(to, from, next) {
    let user = store.getters['auth/user']
    if (user) {
        switch (user.user_type) {
            case 'super_admin':
                next({ name: 'super_admin.dashboard' })
                break;
            default:
                next({ name: 'guest.dashboard' })
                break;
        }
    } else {
        next()
    }
}

export default AuthGuard

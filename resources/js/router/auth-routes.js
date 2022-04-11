import lazyLoading from './lazyLoading'
import AuthLayout from '../components/Layouts/Auth'
import AuthGuard from './guards/auth-guard'

export default [
    {
        path: '',
        component: AuthLayout,
        beforeEnter: AuthGuard,
        redirect: { name: 'login' },
        children: [
            {
                name: 'login',
                path: 'login',
                component: lazyLoading('Auth/Login'),
            },
            {
                name: 'forgot-password',
                path: 'forgot-password',
                component: lazyLoading('Auth/ForgotPassword'),
            },
            {
                name: 'reset-password',
                path: 'reset-password/:token',
                component: lazyLoading('Auth/ResetPassword'),
            },
        ],
    },
]

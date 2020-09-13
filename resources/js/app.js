import Vue from 'vue'
import VueRouter from 'vue-router'
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'

Vue.use(VueRouter)

import App from './views/App'
import Home from './views/Home'
import Login from './views/Login'
import Register from './views/Register'
import SingleCertificate from './views/SingleCertificate'
import Checkout from './views/Checkout'
import Confirmation from './views/Confirmation'
import UserBoard from './views/UserBoard'
import Cart from './views/Cart'
import StartSession from './views/StartSession'
import Admin from './views/Admin'
import $ from 'jquery'
import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css'
import ForgotPassword from './views/ForgotPassword'
import ResetPasswordForm from './views/ResetPasswordForm'
import store from './store';
Vue.use(Vuetify);

const router = new VueRouter({
    mode: 'history',
    scrollBehavior() {
        return { x: 0, y: 0 }
    },
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/cart',
            name: 'cart',
            component: Cart,
        },
        {
            path: '/certificates/:id',
            name: 'single-certificates',
            component: SingleCertificate
        },
        {
            path: '/confirmation',
            name: 'confirmation',
            component: Confirmation
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: Checkout,
        },
        {
            path: '/dashboard',
            name: 'userboard',
            component: UserBoard,
            meta: {
                requiresAuth: true,
                is_user: true
            }
        },

        {
            path: '/startsession/:id',
            name: 'start-session',
            component: StartSession,
            meta: {
                requiresAuth: true,
                is_user: true
            }
        },
        {
            path: '/admin/:page',
            name: 'admin-pages',
            component: Admin,
            meta: {
                requiresAuth: true,
                is_admin: true
            }
        },
        {
            path: '/admin',
            name: 'admin',
            component: Admin,
            meta: {
                requiresAuth: true,
                is_admin: true
            }
        },
        {
            path: '/reset-password',
            name: 'reset-password',
            component: ForgotPassword,
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/reset-password/:token',
            name: 'reset-password-form',
            component: ResetPasswordForm,
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/verify/:verification_token',
            name: 'verify',
            component: ResetPasswordForm,
            meta: {
                requiresAuth: false
            }
        },
    ],
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('genius.jwt') == null) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            let user = JSON.parse(localStorage.getItem('genius.user'))
            if (to.matched.some(record => record.meta.is_admin)) {
                if (user.is_admin == 1) {
                    next()
                }
                else {
                    next({ name: 'userboard' })
                }
            }
            else if (to.matched.some(record => record.meta.is_user)) {
                if (user.is_admin == 0) {
                    next()
                }
                else {
                    next({ name: 'admin' })
                }
            }
            next()
        }
    } else {
        next()
    }
})

const app = new Vue({
    el: '#app',
    store,
    beforeCreate() {
        this.$store.commit('initialiseStore');
    },
    components: { App },
    router,
    vuetify: new Vuetify({
        theme: {
            primary: '#b71c1c',
            secondary: '#b0bec5',
            accent: '#8c9eff',
            error: '#b71c1c'
        }
    }),
});
// jQuery.extend(true, jQuery.fn.datetimepicker.defaults, {
//     icons: {
//         time: 'far fa-clock',
//         date: 'far fa-calendar',
//         up: 'fas fa-arrow-up',
//         down: 'fas fa-arrow-down',
//         previous: 'fas fa-chevron-left',
//         next: 'fas fa-chevron-right',
//         today: 'fas fa-calendar-check',
//         clear: 'far fa-trash-alt',
//         close: 'far fa-times-circle'
//     }
// });
// Subscribe to store updates
store.subscribe((mutation, state) => {
    // Store the state object as a JSON string
    localStorage.setItem('store', JSON.stringify(state));
});
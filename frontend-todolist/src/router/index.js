import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue';
import HomeView from '../views/HomeView.vue'

const router = createRouter({
    history: createWebHistory(
        import.meta.env.BASE_URL),
    routes: [{
            path: '/',
            name: 'home',
            component: HomeView,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/about',
            name: 'about',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () =>
                import ('../views/AboutView.vue')
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'registrar',
            component: Register
        }
    ]
})

router.beforeEach((to, from, next) => {
    const publicPages = ['/login', '/register', '/about'];
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = localStorage.getItem('token');

    // trying to access a restricted page + not logged in
    // redirect to login page
    if (authRequired && !loggedIn) {
        next('/login');
    } else {
        next();
    }
});

export default router
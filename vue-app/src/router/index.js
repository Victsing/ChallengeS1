// Composables
import { createRouter, createWebHistory } from 'vue-router'
import Authentification from '@/views/UserAuthentification.vue'
import ResetPassword from '@/views/ResetPassword.vue'

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/Default.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "home" */ '@/views/Home.vue'),
      },
      {
        path: '/authentification',
        name: 'Authentification',
        component: Authentification
      },
      {
        path: '/:token/reset-password',
        name: 'ResetPassword',
        component: ResetPassword
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

export default router

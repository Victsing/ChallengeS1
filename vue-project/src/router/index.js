import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Authentification from '../views/Authentification.vue'
import ResetPassword from '../views/ResetPassword.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/authentification',
      name: 'Authentification',
      component: Authentification
    },
    {
      path: '/:token/reset-password',
      name: 'reset-password',
      component: ResetPassword
    }
  ]
})

export default router

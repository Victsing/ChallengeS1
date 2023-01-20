// Composables
import { createRouter, createWebHistory } from 'vue-router'
import Authentication from '@/views/UserAuthentification.vue'
import ResetPassword from '@/views/ResetPassword.vue'
import LandingPage from '@/views/LandingPage.vue'
import UserProfile from '@/views/UserProfile.vue'
import ValidateAccount from '@/views/ValidateAccount.vue'

const isAuthenticated = () => {
  const token = localStorage.getItem('token');
  if (token) {
    return true;
  }
  return false;
}

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/Default.vue'),
    children: [
      {
        path: '',
        name: 'LandingPage',
        component: LandingPage
      },
      {
        path: '/authentication',
        name: 'authentication',
        component: Authentication
      },
      {
        path: '/:token/reset-password',
        name: 'ResetPassword',
        component: ResetPassword
      },
      {
        beforeEnter: (to, from, next) => {
          if (isAuthenticated()) {
            next();
          } else {
            next('/authentication');
          }
        },
        path: '/profile',
        name: 'UserProfile',
        component: UserProfile
      },
      {
        path: '/:token/email-verification',
        name: 'ValidateAccount',
        component: ValidateAccount
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

export default router

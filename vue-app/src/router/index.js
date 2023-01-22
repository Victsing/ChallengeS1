// Composables
import { createRouter, createWebHistory } from 'vue-router'
import Authentication from '@/views/UserAuthentification.vue'
import Home from '@/views/Home.vue'
import LandingPage from '@/views/LandingPage.vue'
import RegisterCompany from '@/views/RegisterCompany.vue'
import ResetPassword from '@/views/ResetPassword.vue'
import UserProfile from '@/views/UserProfile.vue'
import ValidateAccount from '@/views/ValidateAccount.vue'

const isAuthenticated = () => {
  const token = localStorage.getItem('token');
  if (token) {
    return true;
  }
  return false;
};

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
        beforeEnter: (to, from, next) => {
          if (isAuthenticated()) {
            next();
          } else {
            next('/');
          }
        },
        path: '/home',
        name: 'Home',
        component: Home
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
        path: '/register-company',
        name: 'RegisterCompany',
        component: RegisterCompany,
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
      {
        path: '/admin',
        children: [
          {
            path: '',
            component: () => import('@/views/admin/Admin.vue')
          },
          {
            path: '/list-users',
            component: () => import('@/views/admin/ListUsers.vue')
          }
        ]
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

export default router;

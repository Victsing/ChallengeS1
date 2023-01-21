// Composables
import { createRouter, createWebHistory } from 'vue-router';
import Authentication from '@/views/UserAuthentification.vue';
import ResetPassword from '@/views/ResetPassword.vue';
import LandingPage from '@/views/LandingPage.vue';
import UserProfile from '@/views/UserProfile.vue';

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
        path: '/profile',
        name: 'UserProfile',
        component: UserProfile
      },
      {
        path: '/admin',
        children: [
          {
            path: '',
            component: () => import('@/views/admin/Admin.vue'),
          },
          {
            path: '/list-users',
            component: () => import('@/views/admin/ListUsers.vue'),
          },
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

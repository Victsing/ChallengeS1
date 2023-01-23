// Composables
import { createRouter, createWebHistory } from 'vue-router';

import Authentication from '@/views/UserAuthentification.vue';
import LandingPage from '@/views/LandingPage.vue';
import RegisterCompany from '@/views/RegisterCompany.vue';
import ResetPassword from '@/views/ResetPassword.vue';
import UserProfile from '@/views/UserProfile.vue';
import ValidateAccount from '@/views/ValidateAccount.vue';
import { getDataFromToken } from '@/mixins';
import Home from '@/views/Home.vue';

const isAuthenticated = () => {
  const token = localStorage.getItem('token');
  if (token) {
    return true;
  }
  return false;
};

const isAdmin = () => {
  let tokenData = getDataFromToken();
  if (tokenData.roles.includes('ROLE_ADMIN')) {
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
        beforeEnter: (to, from, next) => {
          if (isAuthenticated()) {
            if (isAdmin()) {
              next('/admin');
            }
            next('/home');
          } else {
            next();
          }
        },
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
        component: RegisterCompany
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
        beforeEnter: (to, from, next) => {
          if (isAdmin()) {
            next();
          } else {
            next('/authentication');
          }
        },
        path: '/admin/',
        children: [
          {
            path: '',
            component: () => import('@/views/admin/Home.vue'),
            name: 'AdminHome'
          },
          {
            path: 'company/sizes',
            name: 'AdminCompanySizes',
            component: () => import('@/views/admin/CompanySizes.vue')
          },
          {
            path: 'company/sectors',
            name: 'AdminCompanySectors',
            component: () => import('@/views/admin/CompanySectors.vue')
          },
          {
            path: 'company/revenues',
            name: 'AdminCompanyRevenues',
            component: () => import('@/views/admin/CompanyRevenues.vue')
          },
          {
            path: 'company/size/new',
            name: 'AdminNewCompanySize',
            component: () => import('@/views/admin/NewCompanySize.vue')
          },
          {
            path: 'company/sector/new',
            name: 'AdminNewCompanySector',
            component: () => import('@/views/admin/NewCompanySector.vue')
          },
          {
            path: 'company/revenue/new',
            name: 'AdminNewCompanyRevenue',
            component: () => import('@/views/admin/NewCompanyRevenue.vue')
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

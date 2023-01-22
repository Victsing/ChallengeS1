// Composables
import { createRouter, createWebHistory } from 'vue-router'
import AdminHome from '@/views/admin/Home.vue'
import AdminNewCompanySize from '@/views/admin/NewCompanySize.vue'
import NewCompanySector from '@/views/admin/NewCompanySector.vue'
import NewCompanyRevenue from '@/views/admin/NewCompanyRevenue.vue'
import AdminCompanySizes from '@/views/admin/CompanySizes.vue'
import AdminCompanySectors from '@/views/admin/CompanySectors.vue'
import AdminCompanyRevenues from '@/views/admin/CompanyRevenues.vue'

import Authentication from '@/views/UserAuthentification.vue'
import Home from '@/views/Home.vue'
import LandingPage from '@/views/LandingPage.vue'
import RegisterCompany from '@/views/RegisterCompany.vue'
import ResetPassword from '@/views/ResetPassword.vue'
import UserProfile from '@/views/UserProfile.vue'
import ValidateAccount from '@/views/ValidateAccount.vue'
import decodeMixin from '@/mixins/decode'

const isAuthenticated = () => {
  const token = localStorage.getItem('token');
  if (token) {
    return true;
  }
  return false;
}

const isAdmin = () => {
  let tokenData = decodeMixin.getDataFromToken();
  if (tokenData.roles.includes('ROLE_ADMIN')) {
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
        beforeEnter: (to, from, next) => {
          if (isAdmin()) {
            next();
          } else {
            next('/authentication');
          }
        },
        path: '/admin',
        component: AdminHome,
        name: 'AdminHome',
      },
      {
        path: 'admin/company/sizes',
        name: 'AdminCompanySizes',
        component: AdminCompanySizes
      },
      {
        path: 'admin/company/sectors',
        name: 'AdminCompanySectors',
        component: AdminCompanySectors
      },
      {
        path: 'admin/company/revenues',
        name: 'AdminCompanyRevenues',
        component: AdminCompanyRevenues
      },
      {
        path: 'admin/company/size/new',
        name: 'AdminNewCompanySize',
        component: AdminNewCompanySize
      },
      {
        path: 'admin/company/sector/new',
        name: 'AdminNewCompanySector',
        component: NewCompanySector
      },
      {
        path: 'admin/company/revenue/new',
        name: 'AdminNewCompanyRevenue',
        component: NewCompanyRevenue
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

export default router

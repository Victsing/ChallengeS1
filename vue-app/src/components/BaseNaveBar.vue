<template>
  <v-app-bar flat :color="color">
    <img :src="AppLogo" alt="logo" class="ml-4" @click="this.$router.push('/home')" />
    <v-app-bar-title>
      {{ title }}
    </v-app-bar-title>
    <template v-slot:append>
      <div v-if="admin">
        <v-btn
          @click="this.$router.push('/admin/companies')"
          >Gérer les entreprises</v-btn>
      </div>
      <div v-if="employer">
        <v-btn
          @click="this.$router.push('/employer/companies')"
          icon="mdi-domain"
          />
      </div>
      <div v-if="isLoggedIn">
        <v-btn
          icon="mdi-email"
          @click="this.$router.push('/job-applications')"
        />
        <v-btn
          icon="mdi-account"
          @click="this.$router.push('/profile')"
        />
        <v-btn
          icon="mdi-logout"
          @click="logout"
        />
      </div>
    </template>
  </v-app-bar>
</template>

<script setup>
import AppLogo from '../assets/app_logo.svg'
import { useRouter } from 'vue-router'
import { computed } from 'vue'

const router = useRouter()

defineEmits(['navbarHome']);

defineProps({
  title: {
    type: String,
    default: 'Welcome to the Challenge Stack'
  },
  color: {
    type: String,
    default: 'black'
  },
  admin:
  {
    type: Boolean,
    default: false
  },
  employer:
  {
    type: Boolean,
    default: false
  }
})

const logout = () => {
  localStorage.removeItem('token');
  router.push('/');
}

const isLoggedIn = computed(() => {
  return localStorage.getItem('token') !== null;
})
</script>
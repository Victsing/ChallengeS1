<template>
  <v-app-bar flat>
    <img :src="AppLogo" alt="logo" class="ml-4" @click="this.$router.push('/')" />
    <v-app-bar-title>
      Welcome to the Challenge Stack
    </v-app-bar-title>
    <template v-slot:append>
      <v-btn
        v-if="isLoggedIn"
        icon="mdi-logout"
        @click="logout"
      />
    </template>
  </v-app-bar>
</template>

<script setup>
import AppLogo from '../assets/app_logo.svg'
import { useRouter } from 'vue-router'
import { computed } from 'vue'

const router = useRouter()

const logout = () => {
  localStorage.removeItem('token');
  router.push('/');
}

const isLoggedIn = computed(() => {
  return localStorage.getItem('token') !== null;
})
</script>
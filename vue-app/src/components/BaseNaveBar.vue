<template>
  <v-app-bar flat :color="props.color">
    <img :src="AppLogo" alt="logo" class="ml-4" @click="logoRedirect" />
    <v-app-bar-title>
      {{ props.title }}
    </v-app-bar-title>
    <template v-slot:append>
      <div v-if="props.admin">
        <v-btn
          @click="this.$router.push('/admin/companies')"
          >Gérer les entreprises</v-btn>
      </div>
      <div v-if="props.employer">
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

const props = defineProps({
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

const logoRedirect = () => {
  if (props.admin) {
    router.push('/admin');
  } else {
    router.push('/home');
  }
}
</script>
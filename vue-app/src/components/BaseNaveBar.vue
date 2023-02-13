<template>
  <v-app-bar flat :color="props.color">
    <v-app-bar-title>
      <v-btn icon="mdi-home" @click="redirectHome" />
      {{ props.title }}
    </v-app-bar-title>
    <template v-slot:append>
      <div v-if="props.employer">
        <v-btn
          @click="this.$router.push('/employer/company')"
          icon="mdi-domain"
          />
      </div>
      <div v-if="props.user && !props.admin && !props.employer">
        <v-btn
          @click="this.$router.push('/appointments')"
          icon="mdi-calendar-clock"
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
import { useRouter } from 'vue-router'
import { computed } from 'vue'

const router = useRouter()

defineEmits(['navbarHome']);

const props = defineProps({
  title: {
    type: String,
    default: 'Larudakoté'
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
  },
  user:
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

const redirectHome = () => {
  if (props.admin) {
    router.push('/admin');
  } else {
    router.push('/home');
  }
}

const logoRedirect = () => {
  if (props.admin) {
    router.push('/admin');
  } else {
    router.push('/home');
  }
}
</script>
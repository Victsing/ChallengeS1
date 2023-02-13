<template>
  <v-app-bar flat :color="color" class="app-bar">
    <!-- <img :src="AppLogo" alt="logo" class="ml-4" @click="handleLogoClick"/> -->
    <v-app-bar-title>{{ title }}</v-app-bar-title>
    <template v-slot:append>
      <div v-if="isAdmin">
        <v-btn @click="goToAdminCompanies">Gérer les entreprises</v-btn>
      </div>
      <div v-if="isEmployer">
        <v-btn @click="goToEmployerCompany" icon="mdi-domain"/>
      </div>
      <div v-if="isUser">
        <v-btn @click="goToAppointments" icon="mdi-calendar-clock"/>
      </div>
      <div v-if="isLoggedIn">
        <v-btn @click="goToJobApplications" icon="mdi-email"/>
        <v-btn @click="goToProfile" icon="mdi-account"/>
        <v-btn @click="logout" icon="mdi-logout"/>
      </div>
    </template>
  </v-app-bar>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      isLoggedIn: 'isLoggedIn'
    }),
    isAdmin() {
      return this.admin
    },
    isEmployer() {
      return this.employer
    },
    isUser() {
      return this.user
    }
  },
  data() {
    return {
      title: 'Larudakoté',
      color: 'black',
      admin: false,
      employer: false,
      user: false
    }
  },
  methods: {
    ...mapActions({
      logout: 'logout'
    }),
    handleLogoClick() {
      if (this.isAdmin) {
        this.$router.push('/admin')
      } else {
        this.$router.push('/home')
      }
    },
    goToAdminCompanies() {
      this.$router.push('/admin/companies')
    },
    goToEmployerCompany() {
      this.$router.push('/employer/company')
    },
    goToAppointments() {
      this.$router.push('/appointments')
    },
    goToJobApplications() {
      this.$router.push('/job-applications')
    },
    goToProfile() {
      this.$router.push('/profile')
    }
  }
}
</script>

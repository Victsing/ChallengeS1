<template>
  <v-app-bar flat>
    <img :src="AppLogo" alt="logo" class="ml-4" @click="this.$router.push('/')" />
    <v-app-bar-title>
      Welcome to the Challenge Stack
    </v-app-bar-title>
  </v-app-bar>
</template>

<script setup>
import AppLogo from '../assets/app_logo.svg'

</script>
<template>
  <div class="d-flex align-center ">
    <div>
      <v-img height="32" width="32" :src="AppLogo" />
    </div>
    <div class="ml-4 ">
      <BaseTitle>{{ props.title }}</BaseTitle>
    </div>
  </div>
</template>

<script setup>
import BaseTitle from './BaseTitle.vue';
import AppLogo from '../assets/app_logo.svg';
const props = defineProps({
  title: {
    type: String,
    default: 'Challenge stack S1',
  },
});
</script>
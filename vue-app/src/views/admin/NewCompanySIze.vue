<template>
  <BaseNaveBar
    title="Admin panel"
  />
  <h1>
    NewCompanySize
  </h1>
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import { onMounted } from 'vue';

onMounted(() => {
  console.log('NewCompanySize mounted');
});
</script>
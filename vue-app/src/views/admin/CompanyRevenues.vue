<template>
  <BaseNaveBar
    title="Admin panel"
  />
  <h1>
    CompanyRevenues
  </h1>
  <v-btn
    color="primary"
    to="/admin/company/revenue/new"
  >
    New Company Revenue
  </v-btn>
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import AdminApi from '@/backend/AdminApi';
import { onMounted, ref } from 'vue';

let CompanyRevenues = ref([]);

onMounted(() => {
  console.log('CompanyRevenues mounted');
  AdminApi.getCompanyRevenues()
    .then((response) => {
      CompanyRevenues.value = response.data["hydra:member"]
    })
    .catch((error) => {
      console.log(error);
    });
});
</script>
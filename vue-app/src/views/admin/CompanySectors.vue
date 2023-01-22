<template>
  <BaseNaveBar
    title="Admin panel"
  />
  <h1>
    CompanyRevenues
  </h1>
  <v-btn
    color="primary"
    to="/admin/company/sector/new"
  >
    New Company Sector
  </v-btn>
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import AdminApi from '@/backend/AdminApi';
import { onMounted, ref } from 'vue';

let companySectors = ref([]);

onMounted(() => {
  console.log('CompanySectors mounted');
  AdminApi.getCompanySectors()
    .then((response) => {
      companySectors.value = response.data["hydra:member"]
    })
    .catch((error) => {
      console.log(error);
    });
});
</script>
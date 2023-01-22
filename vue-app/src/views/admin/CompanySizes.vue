<template>
  <BaseNaveBar
    title="Admin panel"
  />
  <h1>
    CompanySizes
  </h1>
  <v-btn
    color="primary"
    to="/admin/company/size/new"
  >
    New Company Size
  </v-btn>
  <!-- Faire un tableau pour afficher la liste des tailles existantes -->
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import AdminApi from '@/backend/AdminApi';
import { onMounted, ref } from 'vue';

let companySizes = ref([]);

onMounted(() => {
  console.log('CompanySizes mounted');
  AdminApi.getCompanySizes()
    .then((response) => {
      companySizes.value = response.data["hydra:member"]
    })
    .catch((error) => {
      console.log(error);
    });
});
</script>
<template>
  <BaseNaveBar
    title="Admin panel"
  />
  <h1>
    NewCompanySector
  </h1>
  <!-- ajouter un v text field avec vmodel sector et @click qui appelle la fonction createSector -->

  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    :timeout="3000"
    @closeSnackbar="snackbar = false"
  />
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import BaseRoundButton from "@/components/BaseRoundButton.vue";
import BaseSnackbar from '@/components/BaseSnackBar.vue';
import AdminApi from '@/backend/AdminApi';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

let sector = ref("");
let snackbar = ref(false);
let snackbarText = ref("");
let snackbarColor = ref("");

let createSector = async (e) => {
  e.preventDefault();
  AdminApi.createCompanySector(sector.value)
    .then(() => {
      router.push("/admin/company/sector");
    })
    .catch(() => {
      snackbarText.value = "Error while creating company sector";
      snackbarColor.value = "error";
      snackbar.value = true;
    });
};
</script>
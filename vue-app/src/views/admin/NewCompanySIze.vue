<template>
  <BaseNaveBar
    title="Admin panel"
  />
  <h1>
    NewCompanySizes
  </h1>
  <!-- ajouter un v text field avec vmodel size et @click qui appelle la fonction createSize -->

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
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import AdminApi from '@/backend/AdminApi';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

let size = ref("");
let snackbar = ref(false);
let snackbarText = ref("");
let snackbarColor = ref("");

let createSize =async (e) => {
  e.preventDefault();
  AdminApi.createCompanySize(size.value)
    .then(() => {
      router.push("/admin/company/size");
    })
    .catch(() => {
      snackbarText.value = "Error while creating company size";
      snackbarColor.value = "error";
      snackbar.value = true;
    });
};
</script>

<style scoped>
  h1 {
    text-align: center;
    margin: 2rem 0;
  }

  form {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .v-text-field {
    margin: 1rem 0;
    width: 40%;
  }

  .submit-button {
    margin-top: 2rem;
  }
</style>

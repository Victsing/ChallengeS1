<template>
  <BaseNaveBar :title="`Bienvenu ${decoded.firstname} ${decoded.lastname}`" :admin="isAdmin" :employer="isEmployer" :user="isUser" />
  <div class="text-center">
    <h1 class="mb-16 mt-16 ">Larudakoté</h1>
    <v-row >
      <v-col class="mr-16" >
        <div class="text-width text-left ">
        <h3 class="mb-8 text-center">À la recherche d'une nouvelle opportuité professionnelle ? </h3>
          
          Nous vous proposons des offres d'emploi
          dans toute la France et vous permettons de postuler en quelques clics. Nous proposons des offres
          d'emploi dans tous les secteurs d'activité, n'hésitez pas à consulter la liste des
          offres d'emploi en cliquant sur le bouton ci-dessous.
        </div>
          <v-btn  color="pink darken-3" class="mt-16" @click="this.$router.push('/jobs')">
            Consulter les offres d'emploi
          </v-btn>
      </v-col>
      <v-col class="ml-16">
        <div class="text-width text-left">
        <h3 class="mb-8 text-center">Vous avez une entreprise et vous souhaitez recruter ?</h3>
        Nous vous proposons de publier vos offres d'emploi sur notre plateforme et de trouver les
          meilleurs profils. Nous vous permettons de publier vos offres d'emploi en quelques
          clics. N'hésitez pas à enregistrer votre entreprise en cliquant sur le bouton ci-dessous.
        </div>
          <v-btn color="pink darken-3" class="mt-16" @click="handleHasCompany()">
            Enregister mon entreprise
          </v-btn>
      </v-col>
    </v-row>
      <v-img :src="HomeImage" alt="home image" max-height="44vh" />
  </div>
  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    :timeout="30000"
    @close-snackbar="snackbar = false"
  />
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import HomeImage from '@/assets/home_image.png';
import { computed, onMounted, ref } from 'vue';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import { useRouter, useRoute } from 'vue-router';

const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

const router = useRouter();
const route = useRoute();

let snackbar = ref(false);
let snackbarText = ref('');
let snackbarColor = ref('success');

const me = ref({});

const handleHasCompany = () => {
  if (hasCompnany == true) {
    snackbar.value = true;
    snackbarText.value = 'Vous avez déjà enregistré une entreprise';
    snackbarColor.value = 'error';
  } else {
    router.push('/register-company')
  }
};
const isAdmin = computed(() => {
  return decoded.roles.includes('ROLE_ADMIN');
});

const isEmployer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});
const isUser = computed(() => {
  return decoded.roles.includes('ROLE_USER');
});

const hasCompnany = computed(() => {
  return me.value.companies.length > 0;
});
onMounted(() => {
  AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
  }).catch((error) => {
    console.log(error);
  });
});
</script>

<style scoped>
.text-width {
  max-width: 572px;
  margin: 0 auto;
}
</style>
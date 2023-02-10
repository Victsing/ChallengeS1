<template>
  <BaseNaveBar :title="`Welcome ${decoded.firstname} ${decoded.lastname}`" :admin="isAdmin" :employer="isEmployer" :user="isUser" />
  <div class="text-center">
    <h2 class="mb-4">Larudakoté</h2>
    <v-row>
      <v-col>
        <div class="text-width">
          Bienvenu sur Larudakoté, si vous êtes à la recherche d'une nouvelle opportuité
          professionnelle, vous êtes au bon endroit. Nous vous proposons des offres d'emploi
          dans toute la France et vous permettons de postuler en quelques clics. Nous des offres
          d'emploi dans tous les secteurs d'activité, n'hésitez pas à consulter la liste des
          offres d'emploi en cliquant sur le bouton ci-dessous.
        </div>
          <v-btn
            color="primary"
            class="mt-4"
            @click="this.$router.push('/jobs')"
          >
            Consulter les offres d'emploi
          </v-btn>
      </v-col>
      <v-col>
        <div class="text-width">
          Vous avez une entreprise et vous souhaitez recruter ? Vous êtes au bon endroit. Nous
          vous proposons de publier vos offres d'emploi sur notre plateforme et de trouver les
          meilleurs profils. Nous vous permettons de publier vos offres d'emploi en quelques
          clics. N'hésitez pas à enregistrer votre entreprise en cliquant sur le bouton ci-dessous.
        </div>
          <v-btn
            color="primary"
            class="mt-4"
            @click="handleHasCompany()"
          >
            Enregister mon entreprise
          </v-btn>
      </v-col>
    </v-row>
      <v-img :src="HomeImage" alt="home image" max-height="66vh" />
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
import HomeImage from '@/assets/home_image.svg';
import { computed, onMounted, ref } from 'vue';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseSnackbar from '@/components/BaseSnackbar.vue';

const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

let snackbar = ref(false);
let snackbarText = ref('');
let snackbarColor = ref('success');

const me = ref({});

const handleHasCompany = () => {
  if (hasCompnany) {
    snackbar.value = true;
    snackbarText.value = 'Vous avez déjà enregistré une entreprise';
    snackbarColor.value = 'error';
  } else {
    this.$router.push('/register-company')
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
    console.log(response.data);
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
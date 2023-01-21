<template>
  <BaseNaveBar :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"/>
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
            @click="this.$router.push('/register-company')"
          >
            Enregister mon entreprise
          </v-btn>
      </v-col>
    </v-row>
      <v-img :src="HomeImage" alt="home image" max-height="66vh" />
  </div>
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import HomeImage from '@/assets/home_image.svg';

const token = localStorage.getItem('token');
const decoded = jwt_decode(token);
</script>

<style scoped>
.text-width {
  max-width: 572px;
  margin: 0 auto;
}
</style>
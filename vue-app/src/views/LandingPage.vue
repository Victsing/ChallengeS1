<template>
  <div class="bg-grey">
    <div class="landing-container">
      <TheLogo class="logo-margin" />
      <div class="d-flex justify-space-between mb-16">
        <BaseTitleWithDescription title="Challenge Stack S1">
          <div class="red--text mb-12">
            Projet réalisé par Pierre BERGER, Mustapha Boukhatem, Locman DIawadoh et Clarence Potel. Ce projet à pour
            but de chercher et de postuler des offres d'emploi. Ce projet est réalisé avec la stack suivante : VueJS et
            Api Platform.
          </div>
          <BaseRoundButton color="geekblue-6" to="/authentication">Se connecter</BaseRoundButton>
          <BaseRoundButton color="neutral-1" variant="outlined" class="ml-2" to="/authentication?tab=two"
            >S'inscrire</BaseRoundButton
          >
        </BaseTitleWithDescription>
        <div>
          <v-img height="420" width="420" :src="LandingImage" />
        </div>
      </div>
    </div>
    <div>
      <h3 class="text-center pt-16 mb-16">Informations supplémentaires</h3>
      <div class="d-flex justify-space-between">
        <div class="d-flex flex-column text-center">
          <v-img height="104" width="104" :src="LandingUser" class="mb-8" />
          Utilisateurs
        </div>
        <div class="d-flex flex-column text-center">
          <v-img height="104" width="104" :src="LandingAdmin" class="mb-8" />
          Administrateurs
        </div>
        <div class="d-flex flex-column text-center">
          <v-img height="104" width="104" :src="LandingConstraints" class="mb-8" />
          Contraintes
        </div>
        <div class="d-flex flex-column text-center">
          <v-img height="104" width="104" :src="LandingChatbot" class="mb-8" />
          ChatBot
        </div>
        <v-btn @click="test">ddd</v-btn>
      </div>
    </div>
  </div>
</template>

<script setup>
import TheLogo from '../components/TheLogo.vue';
import BaseTitleWithDescription from '../components/BaseTitleWithDescription.vue';
import BaseRoundButton from '../components/BaseRoundButton.vue';
import LandingImage from '../assets/landing_image.svg';
import LandingChatbot from '../assets/landing_chatbot.svg';
import LandingUser from '../assets/landing_user.svg';
import LandingAdmin from '../assets/landing_admin.svg';
import LandingConstraints from '../assets/landing_constraints.svg';
</script>

<style lang="scss" scoped>
.landing-container {
  margin-left: 144px !important;
  margin-right: 144px;
}
.max-witdh {
  max-width: 700px;
}
.logo-margin {
  margin-bottom: 200px;
}
.footer-bg {
  width: 100%;
  height: 478px;
  padding-left: 144px;
  padding-right: 144px;
}
</style>

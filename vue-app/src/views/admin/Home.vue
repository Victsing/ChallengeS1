<template>
  <BaseNaveBar title="Admin panel" :admin="admin" />
  <div class="text-center">
    <v-img max-height="40vh" :src="AdminImage" />
  </div>
  <v-container>
    <v-row>
      <v-col>
        <BaseTitle>Bienvenue sur l'interface administrateur</BaseTitle>

        <h2>Vous pouvez accéder à vos différent panel d'administration</h2>
      </v-col>
    </v-row>
    <v-row class="h-100">
      <v-col v-for="(element, index) in cardsContent" :key="index">
        <v-hover v-slot="{ isHovering, props }">
          <a :href="element.link">
            <v-card :elevation="isHovering ? 12 : 2" :class="{ 'on-hover': isHovering }" v-bind="props">
              <v-card-title>{{ element.title }}</v-card-title>
              <v-card-text>
                <p class="mb-4">{{ element.text }}</p>
              </v-card-text>
            </v-card>
          </a>
        </v-hover>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { BaseNaveBar, BaseTitle } from '@/components';
import { getDataFromToken } from '@/mixins';
import AdminImage from '@/assets/admin_image.svg';
import { computed } from 'vue';

let tokenData = getDataFromToken();
let admin = computed(() => {
  return tokenData.roles.includes('ROLE_ADMIN');
});

const cardsContent = [
  {
    title: "Panel d'administration des utilisateurs",
    text: 'Vous pouvez accéder à la liste des utilisateurs, les modifier ou les supprimer.',
    link: '/admin/users'
  },
  {
    title: "Panel d'administration des offres",
    text: 'Vous pouvez accéder à la liste des offres, les modifier ou les supprimer.',
    link: '/admin/jobs'
  },
  {
    title: "Panel d'administration des entreprises",
    text: 'Vous pouvez accéder à la liste des entreprises, les modifier ou les supprimer.',
    link: '/admin/companies'
  },
  {
    title: "Panel d'administration des secteurs d'activité",
    text: 'Vous pouvez accéder à la liste des secteurs d\'activité, les modifier ou les supprimer.',
    link: '/admin/company/sectors'
  },
  {
    title: "Panel d'administration des tailles d'entreprise",
    text: 'Vous pouvez accéder à la liste des tailles d\'entreprise, les modifier ou les supprimer.',
    link: '/admin/company/sizes'
  },
  {
    title: "Panel d'administration des chiffres d'affaires d'entreprise",
    text: 'Vous pouvez accéder à la liste des chiffres d\'affaires d\'entreprise, les modifier ou les supprimer.',
    link: '/admin/company/revenues'
  }
];

</script>
<style lang="scss" scoped>
a {
  text-decoration: none;
}
</style>

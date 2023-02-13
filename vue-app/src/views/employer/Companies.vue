<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <v-card>
    <v-card-title>
      <h2 class="text-center">{{ company.name }}</h2>
    </v-card-title>
    <v-card-text class="text-center">
      <p>{{ company.description }}</p>
      <p>Adresse: {{ company.address }}</p>
      <p>Website: {{ company.website }}</p>
      <p>Crée le: {{ convertDate(company.creationDate) }}</p>
      <p>Chiffre d'affaire: {{ company?.revenue?.revenue }}</p>
      <p>Secteur: {{ company?.sector?.sector }}</p>
      <p>Nombre d'employé: {{ company?.size?.size }}</p>
      <p>Fondateur: {{ company?.founder?.firstname }} {{ company?.founder?.lastname }}</p>
      <div>
        Offres d'emploi: 
        <v-btn
          @click="goToJobsPage"
          icon="mdi-briefcase"
        />
      </div>
    </v-card-text>
  </v-card>
</template>
<script>
import { ref, onMounted, computed } from 'vue'
import CompanyApi from '@/backend/CompanyApi'
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue'
import jwt_decode from 'jwt-decode';

const decoded = jwt_decode(localStorage.getItem('token'));
const me = ref({});
const companyId = ref(null);
const company = ref({});
const isEmployer = computed(() => decoded.roles.includes('ROLE_EMPLOYER'));
const companies = computed(() => me.value.companies);

onMounted(async () => {
  try {
    me.value = (await AuthentificationApi.getMe(decoded.id)).data;
    companyId.value = me.value.companies[0].id;
    company.value = (await CompanyApi.getCompany(companyId.value)).data;
  } catch (error) {
    console.error(error);
  }
});

const convertDate = (date) => {
  const d = new Date(date);
  d.setHours(d.getHours() - 1);
  return d.toLocaleDateString('fr-FR');
};

const goToJobsPage = () => {
  this.$router.push(`/employer/company/${company.id}/jobs`);
};
</script>
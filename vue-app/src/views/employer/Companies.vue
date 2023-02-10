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
      <p> Chiffre d'affaire: {{ company?.revenue?.revenue }}</p>
      <p>Secteur: {{ company?.sector?.sector }}</p>
      <p>Nombre d'employé: {{ company?.size?.size }}</p>
      <p>Fondateur: {{ company?.founder?.firstname }} {{ company?.founder?.lastname }}</p>
      <div>
        Candidatures: 
        <v-btn
        @click="this.$router.push(`/employer/company/${company.id}/jobs`)"
        icon="mdi-briefcase"
        />
      </div>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import CompanyApi from '@/backend/CompanyApi'
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue'
import jwt_decode from 'jwt-decode';

let me = ref({});
const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

const getUser = async () => {
  await AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
    companyId.value = me.value.companies[0].id;
  }).catch((error) => {
    console.log(error);
  });
}

const companyId = ref(null);
let company = ref({});
onMounted( async () => {
  // wait until getUser is done
  await getUser();
  await CompanyApi.getCompany(companyId.value).then((response) => {
    console.log(response);
    company.value = response.data;
    console.log(company);
  }).catch((error) => {
    console.log(error);
  });
});
const isEmployer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});

let companies = computed(() => {
  return me.value.companies;
});

const convertDate = (date) => {
  const d = new Date(date)
  d.setHours(d.getHours() - 1)
  return d.toLocaleDateString('fr-FR')
}

</script>
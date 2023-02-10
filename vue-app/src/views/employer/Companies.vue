<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <div class="d-flex">
    <h1>My company</h1>
  </div>
  <v-card>
    <v-card-title>
      <h2>{{ company.name }}</h2>
    </v-card-title>
    <v-card-text>
      <p>{{ company.address }}</p>
      <p>Website: {{ company.website }}</p>
      <p>{{ company.creationDate }}</p>
      <p>{{ company.description }}</p>
      <p>{{ company?.revenue?.revenue }}</p>
      <p>{{ company?.sector?.sector }}</p>
      <p>{{ company?.size?.size }}</p>
      <p>{{ company?.founder?.firstname }} {{ company?.founder?.lastname }}</p>
    </v-card-text>
    <div>
      Candidatures: 
      <v-btn
      @click="this.$router.push(`/employer/company/${company.id}/jobs`)"
      icon="mdi-briefcase"
      />
    </div>
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

</script>
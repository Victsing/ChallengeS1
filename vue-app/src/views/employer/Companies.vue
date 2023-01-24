<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <div class="d-flex">
    <h1>Employer Companies</h1>
    <v-btn
      @click="this.$router.push('/employer/companies/new')"
      color="primary"
      class="mb-16 ml-4"
    >
      Create a new company
    </v-btn>
  </div>
  <v-table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Address</th>
        <th>Website</th>
        <th>Candidates</th>
        <th>Job Offers</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="company in companies" :key="company.id">
        <td>{{ company.name }}</td>
        <td>{{ company.address }}</td>
        <td>{{ company.website }}</td>
        <td>Candidates</td>
        <td>
          <v-btn
            @click="this.$router.push(`/employer/company/${company.id}/jobs`)"
            icon="mdi-briefcase"
          />
        </td>
        <td>
          <!-- <v-btn
            @click="this.$router.push(`/employer/companies/${company.id}`)"
            icon="mdi-pencil"
          />
          <v-btn
            @click="deleteCompany(company.id)"
            icon="mdi-delete"
          /> -->
          Actions
        </td>
      </tr>
    </tbody>
  </v-table>
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
onMounted(() => {
  AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
    console.log(me);
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
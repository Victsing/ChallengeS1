<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <div class="d-flex">
    <h1>Company Jobs</h1>
    <v-btn
    @click="this.$router.push(`/employer/company/${route.params.id}/jobs/new`)"
    color="primary"
    class="mb-16 ml-4"
    >
    Create a new company
  </v-btn>
</div>
<v-table>
  <thead>
    <tr>
      <th>Title</th>
      <th>City</th>
      <th>country</th>
      <th>Salary</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="job in jobs" :key="job.id">
      <td>{{ job.title }}</td>
      <td>{{ job.city }}</td>
      <td>{{ job.country }}</td>
      <td>{{ job.salary }}</td>
      <td>
        <v-btn
          @click="this.$router.push(`/employer/company/${route.params.id}/jobs/${job.id}`)"
          icon="mdi-pencil"
        />
        <v-btn
          @click="deleteJob(job.id)"
          icon="mdi-delete"
        />
      </td>
    </tr>
  </tbody>
</v-table>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import CompanyApi from '@/backend/CompanyApi'
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import { useRoute } from 'vue-router';

const route = useRoute();

let me = ref({});
let company = ref({});
const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

onMounted(() => {
  AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
    console.log(me);
  }).catch((error) => {
    console.log(error);
  });
  CompanyApi.getCompany(route.params.id).then((response) => {
    company.value = response.data;
    console.log(company);
  }).catch((error) => {
    console.log(error);
  });
});

const jobs = computed(() => {
  return company.value.jobAds;
});

let isEmployer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});
</script>
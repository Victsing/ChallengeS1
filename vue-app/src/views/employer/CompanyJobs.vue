<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <div class="d-flex">
    <h1>Company Jobs</h1>
    <v-btn
      @click="this.$router.push(`/employer/company/${route.params.id}/jobs/new`)"
      class="ml-4"
    >
      Create a new job
    </v-btn>
  </div>
  <div v-if="jobs.length === 0">
    <h2 class="text-center">No jobs found</h2>
  </div>
  <v-table v-else>
    <thead>
      <tr>
        <th>Title</th>
        <th>City</th>
        <th>country</th>
        <th>Salary</th>
        <th>Candidates</th>
        <th>Appointments</th>
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
            @click="this.$router.push(`/employer/company/${route.params.id}/jobs/${job.id}/candidates`)"
            icon="mdi-account-multiple"
          />
        </td>
        <td>
          <v-btn
            @click="this.$router.push(`/employer/company/${route.params.id}/jobs/${job.id}/appointments`)"
            icon="mdi-calendar-clock"
          />
        </td>
        <td>
          <v-btn
            @click="this.$router.push(`/employer/company/${route.params.id}/jobs/${job.id}`)"
            icon="mdi-eye"
          />
          <v-btn
            @click="this.$router.push(`/employer/company/${route.params.id}/jobs/${job.id}/edit`)"
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
import CompanyApi from '@/backend/CompanyApi';
import JobsApi from '@/backend/JobsApi';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

let me = ref({});
let company = ref({});
let jobs = ref([]);
const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

onMounted(() => {
  AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
  }).catch((error) => {
    console.log(error);
  });
  CompanyApi.getCompany(route.params.id).then((response) => {
    company.value = response.data;
    jobs.value = company.value.jobAds;
  }).catch((error) => {
    console.log(error);
  });
});

let isEmployer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});

const deleteJob = (id) => {
  JobsApi.deleteJob(id).then((response) => {
    router.go(0);
  }).catch((error) => {
    console.log(error);
  });
};
</script>
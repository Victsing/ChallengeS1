<template>
  <BaseNaveBar
    title="Job Applications"
    :employer="employer"
  />
  <!-- if jobApplications is empty, display a message -->
  <div v-if="jobApplications.length === 0">
    <h2>
      You have not applied for any job yet.
    </h2>
  </div>
  <v-table v-else>
    <thead>
      <tr>
        <th>Job Title</th>
        <th>Job City</th>
        <th>Job Country</th>
        <th>Job Salary</th>
        <th>Job Contract Type</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="jobApplication in jobApplications" :key="jobApplication.id">
        <td>{{ jobApplication.title }}</td>
        <td>{{ jobApplication.city }}</td>
        <td>{{ jobApplication.country }}</td>
        <td>{{ jobApplication.salary }}</td>
        <td>{{ jobApplication.contractType }}</td>
        <td>
          <v-btn
            @click="this.$router.push(`/jobs/${jobApplication.id}`)"
            icon="mdi-eye"
          />
        </td>
      </tr>
    </tbody>
  </v-table>
</template>

<script setup>
import { onMounted, ref, computed, reactive } from 'vue';
import AuthentificationApi from '@/backend/AuthentificationApi';
import JobsApi from '@/backend/JobsApi';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';
import { BaseNaveBar, BaseTitle, BaseSnackBar } from '@/components';

const route = useRoute();
const router = useRouter();

let jobApplications = ref([]);
let token = localStorage.getItem('token');
let decoded = jwt_decode(token);

onMounted(async () => {
  await AuthentificationApi.getMe(decoded.id).then((response) => {
    jobApplications.value = response.data.jobApplications;
  });
});

let employer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});
</script>

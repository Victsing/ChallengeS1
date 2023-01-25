<template>
  <BaseNaveBar
    title="List of jobs"
    :employer="isEmployer"
  />
  <h1>Jobs</h1>
  <v-table>
    <thead>
      <tr>
        <th>Title</th>
        <th>City</th>
        <th>country</th>
        <th>Salary</th>
        <th>Contract Type</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="job in jobs" :key="job.id">
        <td>{{ job.title }}</td>
        <td>{{ job.city }}</td>
        <td>{{ job.country }}</td>
        <td>{{ job.salary }}</td>
        <td>{{ job.contractType }}</td>
        <td>
          <v-btn
            @click="this.$router.push(`/jobs/${job.id}`)"
            icon="mdi-eye"
          />
          <v-btn
            @click="deleteJob(job.id)"
            icon="mdi-email"
            class=""
          />
        </td>
      </tr>
    </tbody>
  </v-table>
</template>

<script setup>
import { BaseNaveBar, BaseTitle, BaseSnackBar } from '@/components';
import AdminApi from '@/backend/AdminApi';
import { onMounted, ref, computed, reactive } from 'vue';
import JobsApi from '@/backend/JobsApi';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';

const jobs = ref([]);

onMounted(async () => {
  await JobsApi.getJobs().then((response) => {
    jobs.value = response.data['hydra:member'];
  });
});

let isEmployer = computed(() => {
  const token = localStorage.getItem('token');
  const decoded = jwt_decode(token);
  return decoded.roles.includes('ROLE_EMPLOYER');
});
</script>

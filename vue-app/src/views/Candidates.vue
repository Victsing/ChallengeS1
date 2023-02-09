<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <h1>Candidates</h1>
  <!-- table for candidates -->
  <v-table v-if="candidates.length > 0">
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Set Appointment</th>
        <th>Dismiss</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="candidate in candidates" :key="candidate.id">
        <td>{{ candidate.firstname }}</td>
        <td>{{ candidate.lastname }}</td>
        <td>
          <v-btn
          @click="this.$router.push(`/candidate/${candidate.id}`)"
          icon="mdi-calendar-clock"
          color="success"
          />
        </td>
        <td> <v-btn
          @click="this.$router.push(`/candidate/${candidate.id}`)"
          icon="mdi-trash-can"
          color="error"
          /></td>
      </tr>
    </tbody>
  </v-table>
  <h2 v-else class="text-center">No candidates found</h2>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import CompanyApi from '@/backend/CompanyApi';
import JobsApi from '@/backend/JobsApi';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import { getDataFromToken } from '@/mixins';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';

const token = localStorage.getItem('token');
const decoded = jwt_decode(token);
const tokenData = getDataFromToken();
const isEmployer = computed(() => tokenData.roles.includes('ROLE_EMPLOYER'));

const route = useRoute();
const router = useRouter();

const candidates = ref([]);

onMounted(() => {
  const id = isEmployer.value ? route.params.jobId : route.params.id;
  JobsApi.getJob(id).then((response) => {
    candidates.value = response.data.candidates;
  }).catch((error) => {
    console.log(error);
  });
});
</script>
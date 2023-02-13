<template>
  <BaseNavBar :title="title" :employer="isEmployer"/>
  <div v-if="hasNoApplications">
    <h2>Vous n'avez postulé à aucun poste pour l'instant.</h2>
  </div>
  <v-table v-else>
    <thead>
      <tr>
        <th>Intitulé du poste</th>
        <th>Ville du poste</th>
        <th>Pays du poste</th>
        <th>Salaire du poste</th>
        <th>Type de contrat</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="application in jobApplications" :key="application.id">
        <td>{{ application.title }}</td>
        <td>{{ application.city }}</td>
        <td>{{ application.country }}</td>
        <td>{{ application.salary }}</td>
        <td>{{ application.contractType }}</td>
        <td>
          <v-btn
            @click="viewApplication(application.id)"
            icon="mdi-eye"/>
        </td>
      </tr>
    </tbody>
  </v-table>
</template>
<script>
import { ref, computed } from 'vue';
import AuthentificationApi from '@/backend/AuthentificationApi';
import { BaseNavBar } from '@/components';
import { useRouter } from 'vue-router';
import jwt_decode from 'jwt-decode';

const router = useRouter();
const jobApplications = ref([]);
const decoded = jwt_decode(localStorage.getItem('token'));

const title = computed(() => 'Mes candidatures');
const isEmployer = computed(() => decoded.roles.includes('ROLE_EMPLOYER'));
const hasNoApplications = computed(() => jobApplications.value.length === 0);

async function fetchApplications() {
  const response = await AuthentificationApi.getMe(decoded.id);
  jobApplications.value = response.data.jobApplications;
}

async function viewApplication(id) {
  router.push(`/jobs/${id}`);
}

export default {
  computed: {
    title,
    isEmployer,
    hasNoApplications,
  },
  setup() {
    fetchApplications();

    return {
      jobApplications,
      viewApplication,
    };
  },
};
</script>
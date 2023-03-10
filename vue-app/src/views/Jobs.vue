<template>
  <BaseNaveBar
    title="List of jobs"
    :employer="isEmployer"
    :user="isUser"
    :admin="isAdmin"
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
            @click="isAdmin ? this.$router.push(`/admin/jobs/${job.id}`) : this.$router.push(`/jobs/${job.id}`)"
            icon="mdi-eye"
          />
          <v-btn
            @click="dialog = true; selectedJob = job"
            icon="mdi-email"
            v-if="!isAdmin"
          />
        </td>
      </tr>
    </tbody>
  </v-table>
  <v-dialog v-model="dialog" persistent>
    <v-card>
      <v-card-text>
        Vous allez postuler pour ce job, êtes-vous sûr ?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="dialog = false; selectedJob = {}">Non</v-btn>
        <v-btn color="warning" @click="candidate(); dialog = false">Oui</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <BaseSnackBar
    v-model="snackbar"
    :snackbar="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    @closeSnackbar="snackbar = false"
  />
</template>

<script setup>
import { BaseNaveBar, BaseSnackBar } from '@/components';
import UserApi from '@/backend/UserApi';
import { onMounted, ref, computed } from 'vue';
import JobsApi from '@/backend/JobsApi';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

let decoded = jwt_decode(localStorage.getItem('token'));


const jobs = ref([]);
let dialog = ref(false);
let selectedJob = ref({});

let snackbar = ref(false);
let snackbarText = '';
let snackbarColor = '';

onMounted(async () => {
  await JobsApi.getJobs().then((response) => {
    jobs.value = response.data['hydra:member'];
  });
});

const candidate = () => {
  if (isCandidate()) {
    snackbar.value = true;
    snackbarText = 'Vous avez déjà postulé pour ce job';
    snackbarColor = 'error';
  } else if (appointments.value.includes(decoded.id)) {
    snackbar.value = true;
    snackbarText = 'Vous avez déjà un entretien pour ce job';
    snackbarColor = 'error';
  } else {
    UserApi.apply(decoded.id, selectedJob.value.id).then(() => {
      router.push('/job-applications');
    }).catch((error) => {
      console.log(error);
    });
  }
};

const isCandidate = (() => {
  return selectedJob.value.candidates.some((candidate) => candidate.id === decoded.id);
});

const appointments = computed(() => {
  return selectedJob.value.appointments.map((appointment) => appointment.candidate.id);
});

let isEmployer = computed(() => {
  const token = localStorage.getItem('token');
  const decoded = jwt_decode(token);
  return decoded.roles.includes('ROLE_EMPLOYER');
});

let isAdmin = computed(() => {
  const token = localStorage.getItem('token');
  const decoded = jwt_decode(token);
  return decoded.roles.includes('ROLE_ADMIN');
});

const isUser = computed(() => {
  return decoded.roles.includes('ROLE_USER');
});
</script>

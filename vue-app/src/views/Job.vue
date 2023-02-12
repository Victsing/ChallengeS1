<template>
  <BaseNaveBar
    title="Job"
    :employer="isEmployer"
    :user="isUser"
  />
  <v-card class="mb-4 mx-auto" max-width="800">
    <v-card-title>
      <h2 class="text-center">Titre: {{ job.title }}</h2>
    </v-card-title>
    <v-card-text>
      <p>Descrition: {{ job.description }}</p>
      <div class="d-flex">
        <p>City: {{ job.city }}</p>
        <p class="ml-4">Country: {{ job.country }}</p>
      </div>
      <p>Salaire: {{ job.salary }}</p>
      <p>Contrat: {{ job.contractType }}</p>
      <p>Durée: {{ job.missionDuration }}</p>
    </v-card-text>
  </v-card>
  <div class="text-center">
    <v-btn
      @click="dialog = true"
      color="primary"
      class="mb-16 ml-4"
      v-if="!isAdmin && !isEmployer"
    >
      Apply
    </v-btn>
    <div v-else>
      <v-btn
        @click="isAdmin ? this.$router.push(`/admin/jobs/${job.id}/edit`) : this.$router.push(`/employer/company/${route.params.id}/jobs/${job.id}/edit`)"
        color="primary"
        class="mb-16 ml-4"
      >
        Edit
      </v-btn>
      <v-btn
        @click="deleteJob(job.id)"
        color="error"
        class="mb-16 ml-4"
      >
        Delete
      </v-btn>
    </div>
    <v-btn
      @click="isAdmin ? this.$router.push('jobs') : this.$router.push(`/employer/company/${route.params.id}/jobs`)"
      color="error"
      class="mb-16 ml-4"
    >
      Back
    </v-btn>
    <v-btn
      @click="isAdmin ? this.$router.push(`/admin/jobs/${job.id}/candidates`) : this.$router.push(`/employer/company/${route.params.id}/jobs/${job.id}/candidates`)"
      color="primary"
      class="mb-16 ml-4"
      v-if="isEmployer"
    >
      Candidates
    </v-btn>
  </div>
  <v-dialog v-model="dialog" persistent>
    <v-card>
      <v-card-text>
        Vous allez postuler pour ce job, êtes-vous sûr ?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="dialog = false">Non</v-btn>
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
import { ref, onMounted, computed } from 'vue'
import JobsApi from '@/backend/JobsApi';
import UserApi from '@/backend/UserApi';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';
import { BaseNaveBar, BaseSnackBar } from '@/components';

const route = useRoute();
const router = useRouter();

let snackbar = ref(false);
let snackbarText = ref('');
let snackbarColor = ref('');

let job = ref({});
let candidates = ref([]);
let dialog = ref(false);

let token = localStorage.getItem('token');
let decoded = jwt_decode(token);

const isCandidate = computed(() => {
  return candidates.value.some((candidate) => candidate.id === decoded.id);
});

const isUser = computed(() => {
  return decoded.roles.includes('ROLE_USER');
});

const isEmployer = computed(() => {
  const token = localStorage.getItem('token');
  const decoded = jwt_decode(token);
  return decoded.roles.includes('ROLE_EMPLOYER');
});

const isAdmin = computed(() => {
  const token = localStorage.getItem('token');
  const decoded = jwt_decode(token);
  return decoded.roles.includes('ROLE_ADMIN');
});

const candidate = () => {
  if (isCandidate.value) {
    snackbar.value = true;
    snackbarText = 'Vous avez déjà postulé pour ce job';
    snackbarColor = 'error';
  } else if (appointments.value.includes(decoded.id)) {
    snackbar.value = true;
    snackbarText = 'Vous avez déjà un entretien pour ce job';
    snackbarColor = 'error';
  } else {
    UserApi.apply(decoded.id, route.params.id).then((response) => {
      router.push('/job-applications');
    }).catch((error) => {
      console.log(error);
    });
  }
}

const deleteJob = (id) => {
  JobsApi.deleteJob(id).then((response) => {
    if (isAdmin.value) {
      router.push('/admin/jobs');
    } else {
      router.push(`/employer/company/${route.params.id}/jobs`);
    }
  }).catch((error) => {
    console.log(error);
  });
};

const appointments = computed(() => {
  return job.value.appointments.map((appointment) => appointment.candidate.id);
});

onMounted(() => {
  const id = (isAdmin.value || isUser.value) ? route.params.id : route.params.jobId;
  JobsApi.getJob(id).then((response) => {
    job.value = response.data;
    candidates.value = response.data.candidates;
  }).catch((error) => {
    console.log(error);
  });
});
</script>
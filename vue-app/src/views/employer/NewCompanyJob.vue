<template>
  <BaseNaveBar :title="`Welcome ${decoded.firstname} ${decoded.lastname}`" :employer="isEmployer" />
  <h1>New company job</h1>
  <v-form>
    <v-text-field v-model="job.title" label="Title" required />
    <v-text-field v-model="job.description" label="Description" required />
    <v-text-field v-model="job.city" label="City" required />
    <v-text-field v-model="job.country" label="Country" required />
    <v-select v-model="job.contractType" :items="contractTypes" label="Contract Type" required />
    <v-text-field v-model="job.salary" type="number" label="Salary" required />
    <v-text-field v-model="job.missionDuration" label="duration" required />
    <v-btn @click="isEditRoute ? updateJob() : addJob()" color="primary" :disabled="disableButton">
      <div v-if="isEditRoute">Update</div>
      <div v-else>Create</div>
    </v-btn>
  </v-form>
  <BaseSnackbar v-model="snackbar" :text="snackbarText" :color="snackbarColor" @close-snackbar="snackbar = false" />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import CompanyApi from '@/backend/CompanyApi';
import AuthentificationApi from '@/backend/AuthentificationApi';
import JobsApi from '@/backend/JobsApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';
import BaseSnackbar from '@/components/BaseSnackBar.vue';

let disableButton = ref(false);

let snackbar = ref(false);
let snackbarText = ref('');
let snackbarColor = ref('success');

const router = useRouter();
const route = useRoute();
let me = ref({});
const token = localStorage.getItem('token');
const decoded = jwt_decode(token);
let jobsCount = ref(0);

let job = ref({
  title: '',
  description: '',
  city: '',
  country: '',
  contractType: '',
  salary: null,
  missionDuration: '',
  company: `/companies/${route.params.id}`
});

const displayPremium = computed(() => {
  return !me.value.premium && jobsCount.value == 2;
});

const isEditRoute = computed(() => {
  return route.name.includes('Edit');
});

onMounted(() => {
  console.log(route.name);
  if (route.name.includes('Edit')) {
    console.log('edit');
    const jobid = isEmployer.value ? route.params.jobId : route.params.id;
    console.log(jobid);
    JobsApi.getJob(jobid)
      .then((response) => {
        job.value = response.data;
      })
      .catch((error) => {
        console.error(error);
      });
  }
  AuthentificationApi.getMe(decoded.id)
    .then((response) => {
      me.value = response.data;
    })
    .catch((error) => {
      console.error(error);
    });
  CompanyApi.getCompany(route.params.id)
    .then((response) => {
      jobsCount.value = response.data.jobAds.length;
    })
    .catch((error) => {
      console.error(error);
    });
});

let isEmployer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});

const isAdmin = computed(() => {
  const token = localStorage.getItem('token');
  const decoded = jwt_decode(token);
  return decoded.roles.includes('ROLE_ADMIN');
});

const isNumber = computed(() => {
  return /^\d+$/.test(job.value.salary);
});

const checkNotEmptyForm = () => {
  return (
    job.value.title != '' &&
    job.value.description != '' &&
    job.value.city != '' &&
    job.value.country != '' &&
    job.value.contractType != '' &&
    job.value.salary != null &&
    job.value.missionDuration != ''
  );
};

let contractTypes = ['Full Time', 'Part Time', 'Internship', 'Freelance', 'Temporary', 'Apprenticeship'];

const addJob = () => {
  disableButton.value = true;
  if (!checkNotEmptyForm()) {
    snackbar.value = true;
    snackbarText.value = 'Veuillez remplir tous les champs';
    snackbarColor.value = 'error';
    disableButton.value = false;
    return;
  } else if (displayPremium.value) {
    snackbar.value = true;
    snackbarText.value = 'You need to be premium to add more than 2 jobs';
    snackbarColor.value = 'error';
    disableButton.value = false;
    return;
  } else if (!isNumber.value) {
    snackbar.value = true;
    snackbarText.value = 'Salary must be a number';
    snackbarColor.value = 'error';
    disableButton.value = false;
    return;
  } else {
    CompanyApi.createJob(job.value)
      .then((response) => {
        router.push(`/employer/company/${route.params.id}/jobs`);
      })
      .catch((error) => {
        console.error(error);
        disableButton.value = false;
      });
  }
};

const updateJob = () => {
  disableButton.value = true;
  const jobid = isEmployer.value ? route.params.jobId : route.params.id;
  JobsApi.updateJob(jobid, {
    title: job.value.title,
    description: job.value.description,
    city: job.value.city,
    country: job.value.country,
    contractType: job.value.contractType,
    salary: job.value.salary,
    missionDuration: job.value.missionDuration
  })
    .then((response) => {
      if (isEmployer.value) {
        router.push(`/employer/company/${route.params.id}/jobs`);
      } else if (isAdmin.value) {
        router.push(`/admin/jobs`);
      }
    })
    .catch((error) => {
      console.error(error);
      disableButton.value = false;
    });
};
</script>

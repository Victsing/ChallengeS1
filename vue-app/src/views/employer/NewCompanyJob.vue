<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <h1>New company job</h1>
  <v-form>
    <v-text-field
      v-model="job.title"
      label="Title"
      required
    />
    <v-text-field
      v-model="job.description"
      label="Description"
      required
    />
    <v-text-field
      v-model="job.city"
      label="City"
      required
    />
    <v-text-field
      v-model="job.country"
      label="Country"
      required
    />
    <v-select
      v-model="job.contractType"
      :items="contractTypes"
      label="Contract Type"
      required
    />
    <v-text-field
      v-model="job.salary"
      type="number"
      label="Salary"
      required
    />
    <v-text-field
      v-model="job.missionDuration"
      label="duration"
      required
    />
    <v-btn
      @click="addJob"
      color="primary"
      :disabled="disableButton"
    >
      Create
    </v-btn>
  </v-form>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import CompanyApi from '@/backend/CompanyApi';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue'
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';

let disableButton = ref(false);

const route = useRoute();
const router = useRouter();
let me = ref({});
const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

let job = ref({
  title: '',
  description: '',
  city: '',
  country: '',
  contractType: '',
  salary: null,
  missionDuration: '',
  company: `/companies/${route.params.id}`,
});

onMounted(() => {
  AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
  }).catch((error) => {
    console.log(error);
  });
});

let isEmployer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});

let contractTypes = [
  'Full Time',
  'Part Time',
  'Internship',
  'Freelance',
  'Temporary',
  'Apprenticeship',
];

const addJob = () => {
  disableButton.value = true;
  CompanyApi.createJob(job.value).then((response) => {
    console.log(response);
    router.push(`/employer/company/${route.params.id}/jobs`);
  }).catch((error) => {
    console.log(error);
    disableButton.value = false;
  });
};
</script>
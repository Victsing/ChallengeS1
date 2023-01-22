<template>
  <BaseNaveBar :title="`Welcome ${decoded.firstname} ${decoded.lastname}`" />
  <v-row class="align-center">
    <v-col>
      <v-img :src="CompanyImage" alt="company_image" max-height="100vh" />
    </v-col>
    <v-col>
      <h2 class="mb-4 text-center">Register your company</h2>
      <v-form>
        <v-text-field
          label="Company name"
          variant="outlined"
          v-model="companyName"
        />
        <v-select
          label="Company size"
          variant="outlined"
          v-model="companySize"
          required
          type="select"
          :items="companySizeOptions"
        />
        <v-text-field
          label="Company creation date"
          variant="outlined"
          v-model="companyCreationDate"
          type="date"
          required
        />
        <v-select
          label="Company revenues"
          variant="outlined"
          v-model="companyRevenues"
          required
          type="select"
          :items="companyRevenuesOptions"
        />
        <v-text-field
          label="Company address"
          variant="outlined"
          v-model="companyAddress"
          required
        />
        <v-select
          label="Company sector"
          variant="outlined"
          v-model="companySector"
          required
          type="select"
          :items="companySectorOptions"
        />
        <v-text-field
          label="Company website"
          variant="outlined"
          v-model="companyWebsite"
        />
        <v-text-field
          label="Company description"
          variant="outlined"
          v-model="companyDescription"
        />
        <v-text-field
          label="Company SIRET"
          variant="outlined"
          v-model="companySiret"
          type="number"
          required
          :rules="rules"
        />
        <v-btn color="primary" @click="registerCompany" :disabled="disableButton">
          Register
        </v-btn>
      </v-form>
    </v-col>
  </v-row>
  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    :timeout="30000"
    @close-snackbar="snackbar = false"
  />
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import jwt_decode from 'jwt-decode';
import CompanyImage from '@/assets/company_image.svg';
import CompanyApi from '@/backend/CompanyApi';

const router = useRouter();

let snackbar = ref(false);
let snackbarText = ref('');
let snackbarColor = ref('');

let disableButton = ref(false);

const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

let companyName = ref('');
let companySize = ref('');
let companyCreationDate = ref('');
let companyRevenues = ref('');
let companyAddress = ref('');
let companySector = ref('');
let companyWebsite = ref('');
let companyDescription = ref('');
let companySiret = ref(null);

const rules = [
  (v) => !!v || 'Required',
  (v) => v.length === 14 || 'Must be 14 digits',
  (v) => /^\d+$/.test(v) || 'Must be digits',
];

const companySizeOptions = [
  '1-10',
  '11-50',
  '51-200',
  '201-500',
  '501-1000',
  '1001-5000',
  '5001-10000',
  '10001+'
]

const companyRevenuesOptions = [
  '0-100k',
  '100k-500k',
  '500k-1M',
  '1M-5M',
  '5M-10M',
  '10M-50M',
  '50M-100M',
  '100M+'
]

const companySectorOptions = [
  'Tech',
  'Health',
  'Finance',
  'Education',
  'Energy',
  'Food',
  'Retail',
  'Transport',
  'Other'
]

const registerCompany = async () => {
  disableButton.value = true;
  CompanyApi.register(
    companyName.value,
    companySize.value,
    companyCreationDate.value,
    companyRevenues.value,
    companyAddress.value,
    companySector.value,
    companyWebsite.value,
    companyDescription.value,
    `/users/${decoded.id}`,
    companySiret.value,
    new Date().toISOString()
  ).then((response) => {
    if (response.status === 201) {
      snackbar.value = true;
      snackbarText.value = 'Company registered successfully';
      snackbarColor.value = 'success';
      setTimeout(() => {
        router.push('/home');
      }, 3000);
    } else {
      snackbar.value = true;
      snackbarText.value = 'Une erreur est survenue, veuillez réessayer'
      snackbarColor.value = 'error';
      disableButton.value = false;
    }
  }).catch(() => {
    snackbar.value = true;
    snackbarText.value = 'Une erreur est survenue, veuillez réessayer'
    snackbarColor.value = 'error';
    disableButton.value = false;
  });
}
</script>
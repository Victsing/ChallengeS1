<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <div class="d-flex">
    <h1>Company Jobs</h1>
    <div v-if="displayPremium" class="d-flex align-center">
      <v-btn
      @click="premiumDialog = true"
      class="ml-4"
      color="success"
      >
      Become Premium
    </v-btn>
    <b class="ml-4">Pour poster plus de deux annonces, vous devez devenir premium</b>
  </div>
    <v-btn
      @click="this.$router.push(`/employer/company/${route.params.id}/jobs/new`)"
      class="ml-4"
      v-else
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
  <v-dialog v-model="premiumDialog" persistent max-width="680">
    <v-card>
      <v-card-title
        class="bg-blue text-center"
      >
        Informations de paiement
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols='12'>
            <v-list-subheader class="grey--text text--lighten-1 pl-0 subheader">CARD NUMBER</v-list-subheader>
            <v-text-field
                single-line outlined label="**** **** **** ****" hide-details v-model="cardNumber"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols='6'>
            <v-list-subheader class="grey--text text--lighten-1 pl-0 subheader">EXPIRATION DATE</v-list-subheader>
            <v-select
                :items="months"
                item-title="name"
                item-value="id"
                outlined
                label="Month"
                v-model="month"
            />
          </v-col>
          <v-col cols='6'>
            <v-list-subheader class="grey--text text--lighten-1 pl-0 subheader">EXPIRATION DATE</v-list-subheader>
            <v-select
                :items="years"
                item-title="name"
                item-value="id"
                outlined
                label="Year"
                v-model="year"
            />
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="handleClose">Annuler</v-btn>
        <v-btn color="white" class="bg-blue" @click="becomePremium">Payer</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    @close-snackbar="snackbar = false"
  />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import CompanyApi from '@/backend/CompanyApi';
import JobsApi from '@/backend/JobsApi';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';
import BaseSnackbar from '@/components/BaseSnackbar.vue';

const route = useRoute();
const router = useRouter();

let me = ref({});
let company = ref({});
let jobs = ref([]);
const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

let snackbar = ref(false);
let snackbarText = ref('');
let snackbarColor = ref('');

let premiumDialog = ref(false);
let cardNumber = ref('');
let month = ref('');
let year = ref('');

const handleClose = () => {
  premiumDialog.value = false;
  cardNumber.value = '';
  month.value = '';
  year.value = '';
}

let months = [
  { id: 1, name: 'January' },
  { id: 2, name: 'February' },
  { id: 3, name: 'March' },
  { id: 4, name: 'April' },
  { id: 5, name: 'May' },
  { id: 6, name: 'June' },
  { id: 7, name: 'July' },
  { id: 8, name: 'August' },
  { id: 9, name: 'September' },
  { id: 10, name: 'October' },
  { id: 11, name: 'November' },
  { id: 12, name: 'December' }
];

let years = [];
let currentYear = new Date().getFullYear();
for (let i = 0; i < 10; i++) {
  years.push({ id: currentYear + i, name: currentYear + i });
}


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

const displayPremium = computed(() => {
  return !me.value.premium && jobs.value.length == 2;
});

const isCardNumberValid = computed(() => {
  return cardNumber.value.length == 16 && !isNaN(cardNumber.value);
});

const isDateValid = computed(() => {
  let currentDate = new Date();
  let selectedDate = new Date(year.value, month.value);
  return selectedDate > currentDate;
});


const becomePremium = () => {
  cardNumber.value = cardNumber.value.replace(/\s/g, '');
  if (!isCardNumberValid.value) {
    snackbarText.value = 'Card number is not valid';
    snackbarColor.value = 'error';
    snackbar.value = true;
    return;
  } else if (!isDateValid.value) {
    snackbarText.value = 'Card is expired';
    snackbarColor.value = 'error';
    snackbar.value = true;
    return;
  } else {
    snackbarText.value = 'Payment successful';
    snackbarColor.value = 'success';
    snackbar.value = true;
    handleClose();
  }
}

const deleteJob = (id) => {
  JobsApi.deleteJob(id).then((response) => {
    router.go(0);
  }).catch((error) => {
    console.log(error);
  });
};
</script>
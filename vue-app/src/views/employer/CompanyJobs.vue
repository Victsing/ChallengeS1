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
        Informations de paiement utilisateur
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols='12'>
            <v-list-subheader class="grey--text text--lighten-1 pl-0 subheader">Name</v-list-subheader>
            <v-text-field
                single-line outlined label="Name" v-model="name"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols='12'>
            <v-list-subheader class="grey--text text--lighten-1 pl-0 subheader">Email</v-list-subheader>
            <v-text-field
              single-line outlined label="Email" v-model="email"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols='12'>
            <div class="grey--text text--lighten-1 pl-0 subheader" id="card-element">CARD NUMBER</div>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="handleClose">Annuler</v-btn>
        <v-btn color="white" class="bg-blue" @click="">Payer</v-btn>
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
import {ref, onMounted, computed, reactive} from 'vue'
import CompanyApi from '@/backend/CompanyApi';
import JobsApi from '@/backend/JobsApi';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import { useRoute, useRouter } from 'vue-router';
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import { loadStripe } from "@stripe/stripe-js";

const route = useRoute();
const router = useRouter();
let stripe = null;
let loading = ref(true);
let elements = null;

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
let name = ref('');
let email = ref('');
let cardElement = reactive({});
const handleClose = () => {
  premiumDialog.value = false;
  cardNumber.value = '';
  name.value =''
}

onMounted(async () => {
  stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY);
  let elements = stripe.elements()

  loading.value = false;

  async function handleSubmit(event) {
    if (loading.value) return;
    loading.value = true;
    const { name, email } = Object.fromEntries(
      new FormData(event.target)
    );
    console.log("here", name, email);

    const billingDetails = {
      name,
      email
    };

    const cardElement = elements.getElement("card");

    cardElement.mount("#card-element");
    try {

      const response = await fetch("http://localhost:5111/stripe", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ amount: 1999 })
      });
      const { secret } = await response.json();
      console.log("secret", secret);

      const paymentMethodReq = await stripe.createPaymentMethod({
        type: "card",
        card: cardElement,
        billing_details: billingDetails
      });

      console.log("error?", paymentMethodReq);

      const { error } = await stripe.confirmCardPayment(secret, {
        payment_method: paymentMethodReq.paymentMethod.id
      });
      loading.value = false;
      console.log("error?", error);
      await router.push("/success");
    } catch (error) {
      console.log("error", error);
      loading.value = false;
    }
  }


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
  return !me.value.premium && jobs.value.length === 2;
});

const deleteJob = (id) => {
  JobsApi.deleteJob(id).then((response) => {
    router.go(0);
  }).catch((error) => {
    console.log(error);
  });
};
</script>
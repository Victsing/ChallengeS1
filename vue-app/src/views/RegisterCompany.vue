<template>
  <BaseNaveBar :title="`Welcome ${user.firstname} ${user.lastname}`" />
  <v-row class="align-center">
    <v-col>
      <v-img :src="CompanyImage" alt="company_image" max-height="100vh" />
    </v-col>
    <v-col>
      <h2 class="mb-4 text-center">Register your company</h2>
      <v-form>
        <v-text-field label="Company name" variant="outlined" v-model="companyName" />
        <v-select
          label="Company size"
          variant="outlined"
          required
          type="select"
          :items="companySizes"
          v-model="companySize"
          item-value="id"
          item-title="size"
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
          v-model="companyRevenue"
          required
          type="select"
          :items="companyRevenues"
          item-value="id"
          item-title="revenue"
        />
        <v-text-field label="Company address" variant="outlined" v-model="companyAddress" required />
        <v-select
          label="Company sector"
          variant="outlined"
          v-model="companySector"
          required
          type="select"
          :items="companySectors"
          item-value="id"
          item-title="sector"
        />
        <v-text-field label="Company website" variant="outlined" v-model="companyWebsite" />
        <v-text-field label="Company description" variant="outlined" v-model="companyDescription" />
        <v-text-field
          label="Company SIRET"
          variant="outlined"
          v-model="companySiret"
          type="number"
          required
          :rules="rules"
        />
        <v-btn color="primary" @click="registerCompany" :disabled="disableButton"> Register </v-btn>
      </v-form>
    </v-col>
  </v-row>
  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    @close-snackbar="snackbar = false"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import CompanyImage from '@/assets/company_image.svg';
import CompanyApi from '@/backend/CompanyApi';
import { getDataFromToken } from '@/mixins';
import AuthentificationApi from '@/backend/AuthentificationApi';

const router = useRouter();

let companySizes = ref([]);
let companyRevenues = ref([]);
let companySectors = ref([]);
const user = ref(getDataFromToken());

onMounted(() => {
  CompanyApi.getCompanySizes().then((response) => {
    companySizes.value = response.data['hydra:member'];
  });
  CompanyApi.getCompanyRevenues().then((response) => {
    companyRevenues.value = response.data['hydra:member'];
  });
  CompanyApi.getCompanySectors().then((response) => {
    companySectors.value = response.data['hydra:member'];
  });
});

let snackbar = ref(false);
let snackbarText = ref('');
let snackbarColor = ref('');

let disableButton = ref(false);

let companyName = ref('');
let companySize = ref('');
let companyCreationDate = ref('');
let companyRevenue = ref('');
let companyAddress = ref('');
let companySector = ref('');
let companyWebsite = ref('');
let companyDescription = ref('');
let companySiret = ref(null);

const rules = [
  (v) => !!v || 'Required',
  (v) => v.length === 14 || 'Must be 14 digits',
  (v) => /^\d+$/.test(v) || 'Must be digits'
];

const registerCompany = async () => {
  disableButton.value = true;
  if (companySiret.value?.length > 14 || !/^\d+$/.test(companySiret.value)) {
    snackbar.value = true;
    snackbarText.value = 'SIRET must be 14 digits';
    snackbarColor.value = 'error';
    disableButton.value = false;
  } else if (companyCreationDate.value > new Date().toISOString().split('T')[0]) {
    snackbar.value = true;
    snackbarText.value = 'Company creation date must be before today';
    snackbarColor.value = 'error';
    disableButton.value = false;
  }  else {
    let compagnyCheck;
    try {
      compagnyCheck = await CompanyApi.checkCompany(
        companyName.value,
        companyAddress.value,
        user.value.lastname + ' ' + user.value.firstname,
        companySiret.value,
        companyCreationDate.value
      );
    } catch (error) {
      snackbar.value = true;
      snackbarText.value = "Erreur serveur de l'api vérification de la société, veuillez réessayer";
      snackbarColor.value = 'error';
      disableButton.value = false;
      return;
    }

    if (compagnyCheck.status !== 200) {
      snackbar.value = true;
      snackbarText.value = `${compagnyCheck.status} ${compagnyCheck.error}`;
      snackbarColor.value = 'error';
      disableButton.value = false;
      return;
    }
    CompanyApi.register(
      companyName.value,
      `/company_size_options/${companySize.value}`,
      companyCreationDate.value,
      `/company_revenue_options/${companyRevenue.value}`,
      companyAddress.value,
      `/company_sector_options/${companySector.value}`,
      companyWebsite.value,
      companyDescription.value,
      `/users/${user.value.id}`,
      companySiret.value,
      new Date().toISOString()
    )
      .then(async (response) => {
        if (response.status === 201) {
          await AuthentificationApi.addRoleEmployer(user.value.id);
          snackbar.value = true;
          snackbarText.value = 'Company registered successfully, you will be redirected to the login page';
          snackbarColor.value = 'success';
          setTimeout(() => {
            localStorage.removeItem('token');
            router.push('/authentication');
          }, 3000);
        } else {
          snackbar.value = true;
          snackbarText.value = 'Une erreur est survenue, veuillez réessayer';
          snackbarColor.value = 'error';
          disableButton.value = false;
        }
      })
      .catch(() => {
        snackbar.value = true;
        snackbarText.value = 'Une erreur est survenue, veuillez réessayer';
        snackbarColor.value = 'error';
        disableButton.value = false;
      });
  }
};
</script>

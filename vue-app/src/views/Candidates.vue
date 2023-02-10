<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <h1>Candidates</h1>
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
          @click="handleAction(candidate, 'setAppointment')"
          icon="mdi-calendar-clock"
          color="success"
          />
        </td>
        <td> <v-btn
          @click="handleAction(candidate, 'dismiss')"
          icon="mdi-trash-can"
          color="error"
          /></td>
      </tr>
    </tbody>
    <v-dialog v-model="dialog" persistent>
      <v-card>
        <v-card-text>
          Vous vous apprétez à <strong class="text-warning">supprimer</strong> cette candidature.
          Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="handleDialog(false)">Non</v-btn>
          <v-btn color="warning" @click="remove">Oui</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="newDialog" persistent>
      <v-card>
        <v-card-text>
          <v-form>
            <v-text-field
              label="Datetime"
              type="datetime-local"
              required
              step="60"
              v-model="appointment.time"
            />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="newDialog = false">Annuler</v-btn>
          <v-btn color="warning" @click="setAppointment(); newDialog = false">Ajouter</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <BaseSnackbar
      v-model="snackbar"
      :text="snackbarText"
      :color="snackbarColor"
      :timeout="30000"
      @close-snackbar="snackbar = false"
    />
  </v-table>
  <h2 v-else class="text-center">No candidates found</h2>
</template>

<script setup>
import { ref, onMounted, computed, reactive } from 'vue'
import CompanyApi from '@/backend/CompanyApi';
import JobsApi from '@/backend/JobsApi';
import AppointmentApi from '@/backend/AppointmentApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import BaseSnackbar from '@/components/BaseSnackbar.vue';
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
const appointments = ref([]);

const snackbar = ref(false);
const snackbarText = ref('');
const snackbarColor = ref('success');

let dialogElement = reactive({});
const dialog = ref(false);
const newDialog = ref(false);
const appointment = ref({
  candidate: '',
  job: `/job_ads/${route.params.jobId}`,
  time: '',
  accepted: null,
});

const handleAction = (elem, action) => {
  dialogElement = elem;
  if (action == 'setAppointment') {
    newDialog.value = true;
  } else {
    dialog.value = true;
  }
};

const handleDialog = (value) => {
  dialog.value = value;
  if (!value) {
    dialogElement = {};
  }
};

const setAppointment = () => {
  if (checkAppointment(appointment.value.time)) {
    snackbar.value = true;
    snackbarText.value = 'Un rendez-vous existe déjà à cette date et heure';
    snackbarColor.value = 'error';
    return;
  } else if (checkDate(appointment.value.time)) {
    snackbar.value = true;
    snackbarText.value = 'La date et l\'heure doivent être supérieures à la date et l\'heure actuelle';
    snackbarColor.value = 'error';
    return;
  }
  appointment.value.candidate = `/users/${dialogElement.id}`;
  AppointmentApi.create(appointment.value).then((response) => {
    snackbar.value = true;
    snackbarText.value = 'Rendez-vous ajouté avec succès';
    snackbarColor.value = 'success';
    router.push({ name: 'EmployerCompanyJobAppointments' });
  }).catch((error) => {
    snackbar.value = true;
    snackbarText.value = 'Une erreur est survenue, veuillez réessayer';
    snackbarColor.value = 'error';
    console.log(error);
  });
};

// function to check if appointment.time already exists in appointments
const checkAppointment = (time) => {
  // add +00:00 to time
  time = time + ':00+00:00';
  console.log(time);
  const index = appointments.value.findIndex((appointment) => appointment.time === time);
  if (index !== -1) {
    return true;
  }
  return false;
};

// function to check if date is in the past
const checkDate = (date) => {
  const today = new Date();
  const dateToCheck = new Date(date);
  console.log(today);
  if (dateToCheck < today) {
    return true;
  }
  return false;
};

const remove = () => {
  const index = candidates.value.findIndex((candidate) => candidate.id === dialogElement.id);
  candidates.value.splice(index, 1);
  // JobsApi.deleteCandidate(candidates.value, route.params.jobId).then((response) => {
  //   snackbar.value = true;
  //   snackbarText.value = 'Candidature supprimée avec succès';
  //   snackbarColor.value = 'success';
  // }).catch((error) => {
  //   snackbar.value = true;
  //   snackbarText.value = 'Une erreur est survenue, veuillez réessayer';
  //   snackbarColor.value = 'error';
  //   console.log(error);
  // });
  dialog.value = false;
};

onMounted(() => {
  const id = isEmployer.value ? route.params.jobId : route.params.id;
  JobsApi.getJob(id).then((response) => {
    candidates.value = response.data.candidates;
    appointments.value = response.data.appointments;
  }).catch((error) => {
    console.log(error);
  });
});
</script>
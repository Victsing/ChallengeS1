<template>
  <BaseNaveBar :title="`Welcome ${decoded.firstname} ${decoded.lastname}`" :employer="isEmployer" :user="isUser" />
  <h1>My Appointments</h1>
  <div v-if="appointments.length === 0">
    <h2 class="text-center">No appointments found</h2>
  </div>
  <v-table v-else>
    <thead>
      <tr>
        <th>Job Title</th>
        <th>Job Contract</th>
        <th>Job Salary</th>
        <th>Appointment</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="appointment in my_appointments" :key="appointment.id">
        <td>{{ appointment.job.title }}</td>
        <td>{{ appointment.job.contractType }}</td>
        <td>{{ appointment.job.salary }}</td>
        <td>{{ convertDate(appointment.time) }}</td>
        <td>
            <v-icon v-if="appointment.accepted === true" color="green">mdi-check</v-icon>
            <v-icon v-else-if="appointment.accepted === false" color="red">mdi-close</v-icon>
            <v-icon v-else color="orange">mdi-clock-outline</v-icon>
        </td>
        <td>
          <v-btn
            @click="handleAction(appointment, true)"
            icon="mdi-check"
            color="success"
            :disabled="appointment.accepted === true || appointment.accepted === false"
          />
          <v-btn
            @click="handleAction(appointment, false)"
            icon="mdi-close"
            color="error"
            :disabled="appointment.accepted === true || appointment.accepted === false"
          />
          <v-btn
            @click="this.$router.push(`/jobs/${appointment.job.id}`)"
            icon="mdi-eye"
          />
        </td>
      </tr>
    </tbody>
  </v-table>
  <v-dialog v-model="acceptDialog" persistent>
      <v-card>
        <v-card-text>
          Vous vous apprétez à accepter ce rendez-vous, êtes-vous sûr de vouloir le faire ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="acceptDialog = false">Non</v-btn>
          <v-btn color="warning" @click="setAppointment(); acceptDialog = false">Oui</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="denyDialog" persistent>
      <v-card>
        <v-card-text>
          Êtes-vous sûr de ne pas être disponible pour ce rendez-vous ? Si vous refusez ce rendez-vous,
          le recruteur pourra vous proposer un autre rendez-vous.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="denyDialog = false">Non</v-btn>
          <v-btn color="warning" @click="setAppointment(); denyDialog = false">Oui</v-btn>
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
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import BaseNaveBar from "@/components/BaseNaveBar.vue";
import BaseSnackbar from "@/components/BaseSnackbar.vue";
import jwt_decode from "jwt-decode";
import AuthentificationApi from "@/backend/AuthentificationApi";
import AppointmentApi from "@/backend/AppointmentApi";
import { getDataFromToken } from '@/mixins';

const route = useRoute();
const router = useRouter();

const snackbar = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");

const token = localStorage.getItem("token");
const decoded = jwt_decode(token);
const isEmployer = computed(() => decoded.roles.includes("ROLE_EMPLOYER"));
const isUser = computed(() => decoded.roles.includes("ROLE_USER"));

let appointments = ref([]);
let me = ref({});

const appointment = ref(null);
const appointmentStatus = ref(null);

const handleAction = (app, accepted) => {
  appointment.value = app;
  appointmentStatus.value = accepted;
  if (accepted) {
    acceptDialog.value = true;
  } else {
    denyDialog.value = true;
  }
};

const acceptDialog = ref(false);
const denyDialog = ref(false);

const convertDate = (date) => {
  const d = new Date(date)
  d.setHours(d.getHours() - 1)
  return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) + ' le ' + d.toLocaleDateString('fr-FR')
}

const my_appointments = computed(() => {
  return me.value.appointments;
});

const checkAppointment = (time) => {
  time = time + ':00+00:00';
  const index = appointments.value.findIndex((appointment) => appointment.time === time);
  if (index !== -1) {
    return true;
  }
  return false;
};

const checkAppointmentAccepted = (time) => {
  const index = appointments.value.findIndex((appointment) => appointment.time == time && appointment.accepted == true);
  if (index !== -1) {
    return true;
  }
  return false;
};

const setAppointment = () => {
  if (checkAppointmentAccepted(appointment.value.time)) {
    snackbar.value = true;
    snackbarText.value = "You already have an appointment at this time";
    snackbarColor.value = "error";
    return;
  }
  AppointmentApi.update(appointment.value.id, {
    accepted: appointmentStatus.value,
  }).then((response) => {
    const index = appointments.value.findIndex(
      (appointment) => appointment.id === appointment.value.id
    );
    if (appointmentStatus.value) {
      appointments.value[index].accepted = true;
      snackbar.value = true;
      snackbarText.value = "Appointment accepted";
      snackbarColor.value = "success";
    } else {
      appointments.value[index].accepted = false;
      snackbar.value = true;
      snackbarText.value = "Appointment rejected";
      snackbarColor.value = "error";
    }
  }).catch((error) => {
    console.log(error);
    snackbar.value = true;
    snackbarText.value = "Something went wrong";
    snackbarColor.value = "error";
  });
}

onMounted(async () => {
  AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
    appointments.value = response.data.appointments;
  }).catch((error) => {
    console.log(error);
  });
});


</script>
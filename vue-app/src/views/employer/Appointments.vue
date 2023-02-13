<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <h1>Appointments</h1>
  <div v-if="appointments.length === 0">
    <h2 class="text-center">No appointments found</h2>
  </div>
  <v-table v-else>
    <thead>
      <tr>
        <th>Candidate Email</th>
        <th>Candidate Firstname</th>
        <th>Candidate Lastname</th>
        <th>Appointment</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="appointment in appointments" :key="appointment.id">
        <td>{{ appointment.candidate.email }}</td>
        <td>{{ appointment.candidate.firstname }}</td>
        <td>{{ appointment.candidate.lastname }}</td>
        <td>{{ convertDate(appointment.time) }}</td>
        <td>
            <v-icon v-if="appointment.accepted === true" color="green">mdi-check</v-icon>
            <v-icon v-else-if="appointment.accepted === false" color="red">mdi-close</v-icon>
            <v-icon v-else color="orange">mdi-clock-outline</v-icon>
        </td>
        <td>
          <v-btn
            @click="handleAction(appointment.id, 'edit')"
            icon="mdi-pencil"
            color="warning"
            :disabled="appointment.accepted === true"
          />
          <v-btn
            @click="handleAction(appointment.id, 'delete')"
            icon="mdi-delete"
            color="error"
          />
        </td>
      </tr>
    </tbody>
  </v-table>
  <v-dialog v-model="editDialog" persistent>
      <v-card>
        <v-card-text>
          Modifier le rendez-vous
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
          <v-btn @click="editDialog = false">Annuler</v-btn>
          <v-btn color="warning" @click="setAppointment(); editDialog = false">Modifer</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteDialog" persistent>
      <v-card>
        <v-card-text>
          Êtes-vous sûr de vouloir supprimer ce rendez-vous ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="deleteDialog = false">Non</v-btn>
          <v-btn color="warning" @click="deleteAppointment(); deleteDialog = false">Oui</v-btn>
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
import { ref, computed, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import jwt_decode from 'jwt-decode'
import { getDataFromToken } from '@/mixins'
import BaseNaveBar from '@/components/BaseNaveBar.vue'
import JobsApi from '@/backend/JobsApi'
import BaseSnackbar from '@/components/BaseSnackBar.vue'
import AppointmentApi from '@/backend/AppointmentApi'

const token = localStorage.getItem('token')
const decoded = jwt_decode(token)
const tokenData = getDataFromToken()
const isEmployer = computed(() => tokenData.roles.includes('ROLE_EMPLOYER'))

const route = useRoute()
const router = useRouter()

const snackbar = ref(false)
const snackbarText = ref('')
const snackbarColor = ref('')

const editDialog = ref(false)
const deleteDialog = ref(false)

const appointmentId = ref(null)
const appointment = ref({
  time: '',
  accepted: null,
})

const handleAction = (id, action) => {
  appointmentId.value = id
  if (action === 'edit') {
    editDialog.value = true
    // find time of appointment with id and set it to appointment.time and remove the +00:00
    const time = appointments.value.find((appointment) => appointment.id === id).time
    appointment.value.time = time.substring(0, time.length - 6)
  } else if (action === 'delete') {
    deleteDialog.value = true
  }
}

const setAppointment = () => {
  AppointmentApi.update(appointmentId.value, appointment.value).then((response) => {
    snackbar.value = true;
    snackbarText.value = 'Rendez-vous modifié avec succès';
    snackbarColor.value = 'success';
    const index = appointments.value.findIndex((appointment) => appointment.id === appointmentId.value)
    appointments.value[index] = response.data
    appointment.value.time = ''
  }).catch((error) => {
    snackbar.value = true;
    snackbarText.value = 'Une erreur est survenue, veuillez réessayer';
    snackbarColor.value = 'error';
    console.log(error);
  });
};

const deleteAppointment = () => {
  AppointmentApi.delete(appointmentId.value).then((response) => {
    snackbar.value = true;
    snackbarText.value = 'Rendez-vous supprimé avec succès';
    snackbarColor.value = 'success';
    const index = appointments.value.findIndex((appointment) => appointment.id === appointmentId.value)
    appointments.value.splice(index, 1)
  }).catch((error) => {
    snackbar.value = true;
    snackbarText.value = 'Une erreur est survenue, veuillez réessayer';
    snackbarColor.value = 'error';
    console.log(error);
  });
};

const appointments = ref([])

const convertDate = (date) => {
  const d = new Date(date)
  d.setHours(d.getHours() - 1)
  return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) + ' le ' + d.toLocaleDateString('fr-FR')
}

onMounted(() => {
  const id = isEmployer.value ? route.params.jobId : route.params.id
  JobsApi.getJob(id).then((response) => {
    appointments.value = response.data.appointments
  }).catch((error) => {
    console.log(error)
  })
})
</script>

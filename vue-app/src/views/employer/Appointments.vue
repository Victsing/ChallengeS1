<template>
  <BaseNaveBar
    :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
    :employer="isEmployer"
  />
  <h1>Appointments</h1>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import jwt_decode from 'jwt-decode'
import { getDataFromToken } from '@/mixins'
import BaseNaveBar from '@/components/BaseNaveBar.vue'
import JobsApi from '@/backend/JobsApi'

const token = localStorage.getItem('token')
const decoded = jwt_decode(token)
const tokenData = getDataFromToken()
const isEmployer = computed(() => tokenData.roles.includes('ROLE_EMPLOYER'))

const route = useRoute()
const router = useRouter()

const appointments = ref([])

onMounted(() => {
  const id = isEmployer.value ? route.params.jobId : route.params.id
  JobsApi.getJob(id).then((response) => {
    console.log(response.data)
    appointments.value = response.data.appointments
  }).catch((error) => {
    console.log(error)
  })
})
</script>

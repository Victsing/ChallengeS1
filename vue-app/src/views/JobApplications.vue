<template>
  <BaseNavBar :title="title" :employer="isEmployer" />
  <div v-if="hasNoApplications">
    <h2>You have not applied for any job yet.</h2>
  </div>
  <v-table v-else>
    <thead>
      <tr>
        <th>Job Title</th>
        <th>Job City</th>
        <th>Job Country</th>
        <th>Job Salary</th>
        <th>Job Contract Type</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="application in jobApplications" :key="application.id">
        <td>{{ application.title }}</td>
        <td>{{ application.city }}</td>
        <td>{{ application.country }}</td>
        <td>{{ application.salary }}</td>
        <td>{{ application.contractType }}</td>
        <td>
          <v-btn
            @click="viewApplication(application.id)"
            icon="mdi-eye"
          />
        </td>
      </tr>
    </tbody>
  </v-table>
</template>
<script>
import { onMounted, ref, computed } from 'vue';
import AuthentificationApi from '@/backend/AuthentificationApi';
import JobsApi from '@/backend/JobsApi';
import jwt_decode from 'jwt-decode';
import { useRouter } from 'vue-router';
import { BaseNavBar } from '@/components';

const router = useRouter();

export default {
  data() {
    return {
      jobApplications: [],
      token: localStorage.getItem('token'),
      decoded: jwt_decode(localStorage.getItem('token')),
    };
  },
  computed: {
    title() {
      return 'Job Applications';
    },
    isEmployer() {
      return this.decoded.roles.includes('ROLE_EMPLOYER');
    },
    hasNoApplications() {
      return this.jobApplications.length === 0;
    },
  },
  mounted() {
    this.fetchApplications();
  },
  methods: {
    async fetchApplications() {
      const response = await AuthentificationApi.getMe(this.decoded.id);
      this.jobApplications = response.data.jobApplications;
    },
    viewApplication(id) {
      router.push(`/jobs/${id}`);
    },
  },
};
</script>
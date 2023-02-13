import axios from './axios';

export default {
  apply(userId, jobId) {
    return axios.patch(
      `/users/${userId}`,
      {
        jobApplications: [`/job_ads/${jobId}`]
      },
      {
        headers: {
          'Content-Type': 'application/merge-patch+json',
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      }
    );
  },
  getUsers() {
    return axios.get('/users', {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    });
  },
  removeUser(userId) {
    return axios.delete(`/users/${userId}`, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    });
  },
};

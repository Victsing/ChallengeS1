import axios from "./axios";

export default {
  apply(userId, jobId) {
    return axios.patch(`/users/${userId}`, {
      jobApplications: [
        `/job_ads/${jobId}`
      ]
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
};
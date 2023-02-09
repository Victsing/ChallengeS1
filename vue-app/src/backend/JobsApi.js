import axios from "./axios";

export default {
  getJobs() {
    return axios.get("/job_ads");
  },
  getJob(jobId) {
    return axios.get(`/job_ads/${jobId}`);
  },
  updateJob(jobId, job) {
    return axios.patch(`/job_ads/${jobId}`, job,
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  deleteJob(jobId) {
    return axios.delete(`/job_ads/${jobId}`,
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
};
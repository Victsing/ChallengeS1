import axios from "./axios";

export default {
  getJobs() {
    return axios.get("/job_ads");
  },
  getJob(jobId) {
    return axios.get(`/job_ads/${jobId}`);
  },
};
import axios from "./axios";

export default {
  createCompany(company) {
    return axios.post("/employer/companies/", company, {
      headers: {
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  }
}
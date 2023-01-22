import axios from "./axios";

export default {
  register: (companyName, companySize, companyCreationDate, companyRevenues, companyAddress, companySecor, companyWebsite, companyDescription, companyFounder, companySiret, companyCreatedAt) => {
    return axios.post("/companies", {
      name: companyName,
      size: companySize,
      creationDate: companyCreationDate,
      revenues: companyRevenues,
      address: companyAddress,
      sector: companySecor,
      website: companyWebsite,
      description: companyDescription,
      founder: companyFounder,
      siret: companySiret,
      createdAt: companyCreatedAt,
    },
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
}
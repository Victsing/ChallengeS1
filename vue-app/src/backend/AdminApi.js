import axios from "./axios";

export default {
  getCompanySizes() {
    return axios.get("/company_size_options");
  },
  getCompanySectors() {
    return axios.get("/company_sector_options");
  },
  getCompanyRevenues() {
    return axios.get("/company_revenue_options");
  },
  createCompanySize(companySize) {
    return axios.post("/company_size_options",
    {
      size: companySize
    },
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  createCompanySector(companySector) {
    return axios.post("/company_sector_options",
    {
      sector: companySector
    },
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  createCompanyRevenue(companyRevenue) {
    return axios.post("/company_revenue_options",
    {
      revenue: companyRevenue
    },
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  deleteCompanySize(companySizeId) {
    return axios.delete(`/company_size_options/${companySizeId}`,
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  deleteCompanySector(companySectorId) {
    return axios.delete(`/company_sector_options/${companySectorId}`,
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  deleteCompanyRevenue(companyRevenueId) {
    return axios.delete(`/company_revenue_options/${companyRevenueId}`,
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  updateCompanySize(companySizeId, companySize) {
    return axios.patch(`/company_size_options/${companySizeId}`,
    {
      size: companySize
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  updateCompanySector(companySectorId, companySector) {
    return axios.patch(`/company_sector_options/${companySectorId}`,
    {
      sector: companySector
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  updateCompanyRevenue(companyRevenueId, companyRevenue) {
    return axios.patch(`/company_revenue_options/${companyRevenueId}`,
    {
      revenue: companyRevenue
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
};
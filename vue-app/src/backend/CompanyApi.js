import axios from './axios';

export default {
  register: (
    companyName,
    companySize,
    companyCreationDate,
    companyRevenues,
    companyAddress,
    companySecor,
    companyWebsite,
    companyDescription,
    companyFounder,
    companySiret,
    companyCreatedAt
  ) => {
    return axios.post(
      '/companies',
      {
        name: companyName,
        size: companySize,
        creationDate: companyCreationDate,
        revenue: companyRevenues,
        address: companyAddress,
        sector: companySecor,
        website: companyWebsite,
        description: companyDescription,
        founder: companyFounder,
        siret: companySiret,
        createdAt: companyCreatedAt
      },
      {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      }
    );
  },
  getCompanySizes() {
    return axios.get('/company_size_options');
  },
  getCompanySectors() {
    return axios.get('/company_sector_options');
  },
  getCompanyRevenues() {
    return axios.get('/company_revenue_options');
  },
  createJob(job) {
    return axios.post('/job_ads', job, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    });
  },
  getCompany(companyId) {
    return axios.get(`/companies/${companyId}`);
  },
  async checkCompany(companyName, companyAddress, companyFounder, companySiret, companyCreationDate) {
    // API EXTERNE
    const data = {
      name: companyName,
      address: companyAddress,
      founder: companyFounder,
      siret: companySiret,
      creationDate: companyCreationDate
    };
    const res = await fetch(`${import.meta.env.VITE_API_EXTERNAL_URL}/api/check-compagny`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    });
    const json = await res.json();
    if (res.status !== 200) {
      return { status: res.status, error: json.message };
    } else {
      return { status: 200 };
    }
  }
};

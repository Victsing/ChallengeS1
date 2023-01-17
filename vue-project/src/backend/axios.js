import axios from 'axios';

const axiosConfig = {
  baseURL: process.env.API_URL,
};

const customAxiios = axios.create(axiosConfig);

if (localStorage.authorization) {
  customAxiios.defaults.headers.common.authorization = localStorage.authorization;
}

export default customAxiios;
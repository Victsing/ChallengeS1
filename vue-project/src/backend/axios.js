import axios from 'axios';

const axiosConfig = {
  baseURL: 'http://localhost',
};

const customAxiios = axios.create(axiosConfig);

if (localStorage.authorization) {
  customAxiios.defaults.headers.common.authorization = localStorage.authorization;
}

export default customAxiios;
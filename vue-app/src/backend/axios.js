import axios from "axios";

const axiosConfig = {
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    "Content-Type": "application/json"
  },
};

const customAxiios = axios.create(axiosConfig);

if (localStorage.authorization) {
  customAxiios.defaults.headers.common.authorization =
    localStorage.authorization;
}

export default customAxiios;

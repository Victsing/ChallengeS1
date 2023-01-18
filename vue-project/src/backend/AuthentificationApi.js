import axios from "./axios";

export default {
  register: (firstname, lastname, birthdate, email, password, createdAt) => {
    return axios.post("/users", {
      firstname,
      lastname,
      birthdate,
      email,
      password,
      createdAt,
    });
  }
}
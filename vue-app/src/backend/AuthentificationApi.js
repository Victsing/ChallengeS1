import axios from "./axios";

export default {
  register: (userFirstname, userLastname, userBirthdate, userEmail, userPassword, userCreatedAt) => {
    return axios.post("/users", {
      firstname: userFirstname,
      lastname: userLastname,
      birthdate: userBirthdate,
      email: userEmail,
      password: userPassword,
      createdAt: userCreatedAt,
    });
  }
}
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
  },
  login: (userEmail, userPassword) => {
    return axios.post("/authentication_token", {
      email: userEmail,
      password: userPassword,
    });
  },
  validateAccount: (userToken) => {
    return axios.patch("/users/email/verification", {
      token: userToken,
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
      }
    });
  },
  newPassword: (userEmail) => {
    return axios.patch("/users/reset/password/token", {
      email: userEmail,
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
      }
    });
  },
  resetPassword: (userToken, userPassword) => {
    return axios.patch("/users/reset/password", {
      token: userToken,
      password: userPassword,
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
      }
    });
  },
  updateUser: (userId, body) => {
    return axios.patch(`/users/${userId}`, body,
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`,
      }
    });
  },
  addRoleEmployer: (userId) => {
    return axios.patch(`/users/${userId}`,
    {
      "roles": ["ROLE_EMPLOYER"]
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`,
      }
    });
  },
}
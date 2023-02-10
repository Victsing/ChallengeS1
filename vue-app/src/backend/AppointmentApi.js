import axios from "./axios";

export default {
  create(appointment) {
    return axios.post("/appointments", appointment,
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  delete(appointmentId) {
    return axios.delete(`/appointments/${appointmentId}`,
    {
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
  update(appointmentId, value) {
    return axios.patch(`/appointments/${appointmentId}`, value,
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    });
  },
};
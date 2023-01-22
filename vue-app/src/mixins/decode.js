import jwt_decode from 'jwt-decode'

export default {
  getDataFromToken() {
    const token = localStorage.getItem('token');
    const decoded = jwt_decode(token);
    return decoded;
  }
}

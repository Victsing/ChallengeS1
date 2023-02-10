import jwt_decode from 'jwt-decode';

export const getDataFromToken = () => {
  const token = localStorage.getItem('token');
  if (!token) return null;
  return jwt_decode(token);
};

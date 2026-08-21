import axios from 'axios';

// Ensure persistent guest session ID exists in localStorage
let guestSessionId = localStorage.getItem('guest_session_id');
if (!guestSessionId) {
  guestSessionId = 'guest_' + Math.random().toString(36).substring(2, 15) + Date.now().toString(36);
  localStorage.setItem('guest_session_id', guestSessionId);
}

// Configure default headers
axios.defaults.headers.common['X-Session-ID'] = guestSessionId;
axios.defaults.headers.common['Accept'] = 'application/json';

const token = localStorage.getItem('auth_token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export default axios;

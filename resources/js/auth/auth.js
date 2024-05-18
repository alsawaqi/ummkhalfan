import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isLoggingOut: false,
  }),
  getters: {
    isAuthenticated: (state) => !!state.user,
  },
  actions: {
    setUser(userData) {
      this.user = userData;
    },
    async checkAuth() {
      try {
        const response = await axios.get('/api/check-auth');
        if (response.data.authenticated) {
          this.setUser(response.data.user);
          return response.data.user;
        } else {
          this.setUser(null);
        }
      } catch (error) {
        this.user = null;
      }
    },
    async login(credentials) {
        try {
          const response = await axios.post('/api/login', credentials, { withCredentials: true });

          this.setUser(response.data.user);
          location.assign('https://umkhalfan.com');



        } catch (error) {

          this.user = null;
          throw error;
        }
      },
    async register(credentials) {
      try {
        const response = await axios.post('/api/register', credentials, { withCredentials: true });
        this.setUser(response.data.user);

        location.assign('https://umkhalfan.com');

      } catch (error) {
        this.user = null;
        throw error;
      }
    },
    async logout() {


      try {
        await axios.post('/api/logout');
        this.user = null;
        this.token = null;

        location.assign('https://umkhalfan.com');

      } catch (error) {
        throw error;
      }
    },
    async refreshToken() {
      try {
        const response = await axios.post('/api/refresh-token');
        this.token = response.data.access_token;
      } catch (error) {
        throw error;
      }
    },
  },
});

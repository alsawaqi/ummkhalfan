import axios from 'axios';
import { useAuthStore } from './auth/auth.js';
import NProgress from 'nprogress';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'https://umkhalfan.com';






// Start NProgress before a request is made
axios.interceptors.request.use((config) => {
    NProgress.start();
    return config;
}, (error) => {
    NProgress.done();
    return Promise.reject(error);
});


axios.interceptors.response.use(
    (response) => {
        NProgress.done();
        return response;
    },
async (error) => {
      if (error.response && error.response?.status === 401 || error.response?.status === 419) {
        const authStore = useAuthStore();
        try {

          await authStore.refreshToken();
          return axios(error.config);


        } catch (refreshError) {
          authStore.logout();
          location.assign('https://umkhalfan.com/login');

        }
      }

      NProgress.done();
      return Promise.reject(error);
    }
  );


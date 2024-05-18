import './bootstrap';
import { createApp,provide } from "vue";
import router from '../js/router/router';
import App from './App.vue';
import { createPinia } from 'pinia';
import { useAuthStore } from './auth/auth.js';
import axios from 'axios';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';



// Configure NProgress
NProgress.configure({ showSpinner: true });



const app = createApp(App);
const pinia = createPinia();
app.use(pinia);

const authStore = useAuthStore();



async function initializeApp() {
    await authStore.checkAuth();
      app.use(router);
      app.mount('#app');

}

initializeApp();





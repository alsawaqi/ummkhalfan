<template>
 <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Login</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>

                <li class="breadcrumb-item active text-white">Login</li>
            </ol>
        </div>



               <!-- Contact Start -->
               <div class="container-fluid contact py-5">
            <div class="container py-5">
                <div class="p-5 bg-light rounded">
                    <div class="row g-4">


                        <div class="col-lg-12">
                            <form @submit.prevent="login">
                                <input type="email" class="w-100 form-control border-0 py-3 mb-4" placeholder="Enter Your Email" v-model="email">

                                <input type="password" class="w-100 form-control border-0 py-3 mb-4" placeholder="Your Password" v-model="password">
                                 <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary " type="submit" :disabled="isSubmitted">
                                    <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="isSubmitted"></i>

                                    Login

                                </button>
                            </form>



                        </div>

                        <div class="col-6">
                                    <div class="form-check text-start my-3">
                                       <label class="form-check-label" for="Transfer-1">Forgot Password?</label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-check text-start my-3">
                                       <NProgressLink href="/register">New User?</NProgressLink>
                                    </div>
                                  </div>

                    </div>
                </div>
            </div>
               </div>
               <!-- Contact End -->

</template>
<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../auth/auth.js';

import { useToast } from 'vue-toast-notification';
 import 'vue-toast-notification/dist/theme-default.css';

import NProgressLink from '../inc/NProgressLink.vue';


const authStore = useAuthStore();
const email = ref('');
const password = ref('');

const isSubmitted = ref(false);

const toast = useToast();



const login = async () => {
    isSubmitted.value = true;
         try {
                  await authStore.login({ email: email.value, password: password.value });

                }catch (error) {

                  const content = '<i class="fa fa-window-close" aria-hidden="true"></i> ' + error.response.data.message;

                  toast.error(content, {
                        position: 'top-right',
                        duration: 1900,
                        dismissible: true
                    });


                } finally {

                    isSubmitted.value = false;

                 }
};




</script>

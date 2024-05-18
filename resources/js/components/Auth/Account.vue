<template>

       <!-- Single Page Header start -->
       <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">My Profile</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>

                <li class="breadcrumb-item active text-white">My Profile</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">
                    My Account
                </h1>
                <div class="row g-4">
                    <div class="col-lg-12">


                        <div class="row g-4">
                            <!--nav bar-->
                            <NavBar></NavBar>


                            <div class="col-lg-9">
                                <div class="row g-4 justify-content-center">
                                    <div class="spinner-border text-primary text-center" role="status" v-if="loading">
                                      <span class="visually-hidden">Loading...</span>
                                     </div>


                                     <div class="col-md-12 col-lg-6 col-xl-7" v-else>
                                        <form @submit.prevent="updateProfile">
                                            <div class="row">
                                            <div class="col-md-12 col-lg-6">
                                                <div class="form-item w-100">
                                                    <label class="form-label my-3">First Name<sup>*</sup></label>
                                                    <input type="text" class="form-control" name="first_name" id="first_name" v-model="customer.first_name" :disabled="isSubmitting">
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-6">
                                                <div class="form-item w-100">
                                                    <label class="form-label my-3">Last Name<sup>*</sup></label>
                                                    <input type="text" class="form-control" name="last_name" id="last_name" v-model="customer.last_name" :disabled="isSubmitting">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-item">
                                            <label class="form-label my-3">Phone Number<sup>*</sup></label>
                                            <input type="text" class="form-control" name="phone" id="phone" v-model="customer.phone"  @input="handleInput" :disabled="isSubmitting">
                                        </div>

                                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                                                 <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary" :disabled="isSubmitting">Update</button>
                                        </div>

                                        </form>


                                     </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



</template>

<script setup>
import NavBar from './Navbar.vue';

import axios from 'axios';
import {ref , onMounted} from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';



const customer = ref([]);
const loading = ref(false);
const isSubmitting = ref(false);

const toast = useToast();




const getCustomer = async () =>{

    loading.value = true;

 try{

     let response = await axios.get('/customer');
     customer.value = response.data;
     loading.value = false;

   console.log(response.data);

 }catch(error){

       loading.value = false;


 }



}




const handleInput = (event) => {
      const value = event.target.value;
      const numericValue = value.replace(/\D/g, ''); // Remove non-numeric characters
      event.target.value = numericValue.substring(0, 8); // Enforce 8 digit limit
      customer.value.phone = numericValue.substring(0, 8);
    };

const updateProfile = async () => {
      isSubmitting.value = true;

        try{


              await axios.put('/customer', customer.value);
              await getCustomer();

              toast.success('user Updated', {
                        position: 'top-right',
                        duration: 1900,
                        dismissible: true
                    });

              isSubmitting.value = false;



        }catch(error){


            const content = '<i class="fa fa-window-close" aria-hidden="true"></i>' + error.response.data.message;

                toast.error(content, {
                    position: 'top-right',
                    duration: 1900,
                    dismissible: true
                });

            isSubmitting.value = false;




        }
}

onMounted(async ()=>{


     await getCustomer();

});


</script>

<template>

   <!-- Single Page Header start -->
   <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">My Profile</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>

                <li class="breadcrumb-item active text-white">My Profile</li>
            </ol>
        </div>


         <!-- Fruits Shop Start-->
         <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">
                    My Orders
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


                                </div>




                                <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Products</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="(item, index) in order.orderitems" :key="index">
                                            <th scope="row">
                                                <div class="d-flex align-items-center mt-2">
                                                    <img :src="item.product?.first_image?.path" class="img-fluid rounded-circle" style="width: 90px; height: 90px;" :alt="item.product?.first_image?.name" >
                                                </div>
                                            </th>
                                            <td class="py-5">{{ item.product?.name  }}</td>
                                            <td class="py-5">{{ item.price  }} OMR</td>
                                            <td class="py-5">{{ item.quantity  }} Qty</td>
                                            <td class="py-5">{{ item.price * item.quantity }} OMR</td>
                                        </tr>



                                        <tr >
                                            <th scope="row">
                                            </th>

                                            <td class="py-5">
                                                <p class="mb-0 text-dark py-3">Total</p>
                                            </td>
                                            <td class="py-5">
                                                <div class="py-3 border-bottom border-top">
                                                        {{ order.total_amount }} OMR
                                                </div>
                                            </td>

                                            <td class="py-5">
                                                <p class="mb-0 text-dark py-3">Payment Type</p>
                                            </td>
                                            <td class="py-5">

                                                <div class="py-3 border-bottom border-top">
                                                        {{  order.payment_type =='cod' ? 'Cash on Delivery' : order.payment_type =='transfer' ? 'Bank Transfer' : 'Transfer' }}
                                                </div>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                            </div>








                        </div>
                    </div>
                </div>
            </div>
          </div>
        <!-- Fruits Shop End-->







</template>
<script setup>
import { onMounted ,ref} from "vue";
import axios from "axios";
import NavBar from './Navbar.vue';
const loading = ref(false);
const order = ref([]);



const props = defineProps({
                orderref: {
                    type: String,
                    required: true,
                   }
             });



             const getorderdetails = async () => {

                       loading.value = true;

                   try{


                         let response  = await axios.get('/orders/' + props.orderref);

                         order.value = response.data.data;
                         loading.value = false;
                         console.log(response.data.data);

                   }catch(error){


                    if(error.response.status == 404){
                        window.location.assign("https://umkhalfan.com/myorder");
                    }


                    loading.value = false;




                   }




             }


             onMounted(async()=>{
                await getorderdetails()

             })



</script>

<template>

<div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Shop</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><NProgressLink href="/">Home</NProgressLink></li>

                <li class="breadcrumb-item active text-white">Shop</li>
            </ol>
        </div>


         <!-- Single Product Start -->
         <div class="container-fluid py-5 mt-5">
            <div class="container py-5">
                <div class="row g-4 mb-5">
                    <div class="col-lg-8 col-xl-9">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border rounded">
                                    <a href="#">

                                        <img :src="product.first_image?.path" class="img-fluid rounded" :alt="product.first_image?.name">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="fw-bold mb-3">{{ product.name }}</h4>
                                <p class="mb-3">Category: {{ product.categories?.name }}</p>
                                <h5 class="fw-bold mb-3">{{ product.price}} OMR</h5>


                                <div class="input-group quantity mb-5" style="width: 100px;">
                                    <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border" @click="decrement">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center border-0" :value="quantity">
                                    <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border" @click="increment">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    </div>
                                </div>



                                <a href="#" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary" @click.prevent="addToCart"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                            <div class="col-lg-12">
                                <nav>
                                    <div class="nav nav-tabs mb-3">
                                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true">Description</button>

                                    </div>
                                </nav>
                                <div class="tab-content mb-5">
                                    <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                         <div v-html="product.description"></div>

                                    </div>


                                </div>
                            </div>


                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- Single Product End -->


</template>
<script setup>

import { onMounted, ref, computed, watch, defineProps, inject } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
import { useRouter, RouterLink } from 'vue-router';
import NProgressLink from '../components/inc/NProgressLink.vue';
import { useCartStore } from '../components/cart/cart';





const product = ref({});
const router = useRouter();


const cart = ref([]);
let isLoading = ref(false);

const toast = useToast();

const cartStore = useCartStore();


const props = defineProps({
                id: {
                    type: String,
                    required: true,
                   }
             });

             const quantity = ref(1);




       const getproducts = async () =>{

                    try{

                    let response = await axios.get('/product/'+ props.id)
                        product.value = response.data;
                        console.log(response.data);



                    }catch(error){

                        if(error.response.status == 404){
                            window.location.assign("https://umkhalfan.com")


                        }


                    }
                }




             const addToCart = () => {
                    isLoading.value = true;

                    const quantityNumber = parseInt(quantity.value, 10);
                    cartStore.addToCart(product.value, quantityNumber);
                    isLoading.value = false;

                    toast.success('Product added to cart!', { position: 'top-right', duration: 19000, dismissible: true });
                    window.location.assign("https://umkhalfan.com/cart")


                    };

                const increment = () => {

                    if (quantity.value < product.value.stock) {
                            quantity.value++;
                        }

                    };

                    const decrement = () => {
                    if (quantity.value > 1) {
                        quantity.value--;
                    }
                    };


                onMounted(async()=>{
                    await getproducts();

                    const storedCart = localStorage.getItem('cart');
                        if (storedCart) {
                            cart.value = JSON.parse(storedCart);
                        }


             });


</script>

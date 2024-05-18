<template>


  <!-- Navbar start -->
  <div class="container-fluid fixed-top">
            <div class="container topbar bg-primary d-none d-lg-block">
                <div class="d-flex justify-content-between">
                    <div class="top-info ps-2">
                        <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">Oman</a></small>
                        <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">info@umkhalfan.com</a></small>
                    </div>

                </div>
            </div>
            <div class="container px-0">
                <nav class="navbar navbar-light bg-white navbar-expand-xl">
                    <a href="/" class="navbar-brand"><h1 class="text-primary display-6">Um Khalfan</h1></a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">


                            <NProgressLink href="/" class="nav-item nav-link active">Home</NProgressLink>

                            <NProgressLink href="/prouducts" class="nav-item nav-link">Shop</NProgressLink>



                            <NProgressLink href="/contact-us" class="nav-item nav-link">Contact</NProgressLink>


                            <NProgressLink href="#" @click.prevent="logout" class="nav-item nav-link" v-if="customer &&  Object.keys(customer).length > 1">Logout</NProgressLink>
                        </div>
                        <div class="d-flex m-3 me-0">
                             <NProgressLink href="/cart" class="position-relative me-4 my-auto">
                                <i class="fa fa-shopping-bag fa-2x"></i>
                                <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">{{ numberOfCartItems }}</span>
                            </NProgressLink>
                            <NProgressLink href="/myorder" class="my-auto" v-if="customer && Object.keys(customer).length > 1">
                                <i class="fas fa-user fa-2x"></i>
                                {{ customer.name }}
                            </NProgressLink>
                            <NProgressLink v-else href="/login" class="my-auto">
                                <i class="fas fa-user fa-2x"></i>
                                     Login/Register

                            </NProgressLink>

                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


</template>
<script setup>
import { inject,computed, onMounted } from 'vue';
import { useAuthStore } from '../../auth/auth.js';
import { useCartStore } from '../cart/cart.js';
import NProgressLink from '../inc/NProgressLink.vue';


const authStore = useAuthStore();
const cartStore = useCartStore();



const numberOfCartItems = computed(() => cartStore.numberOfCartItems);


const logout = async () => {
    authStore.logout();
};


const customer = inject('customerData');


    onMounted(() => {
        cartStore.loadCartFromLocalStorage();
      })



</script>

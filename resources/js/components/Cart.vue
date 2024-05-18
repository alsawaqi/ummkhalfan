<template>


 <!-- Single Page Header start -->
 <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Cart</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>

                <li class="breadcrumb-item active text-white">Cart</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


         <!-- Cart Page Start -->
         <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="spinner-border text-primary text-center" role="status" v-if="loading">
                                      <span class="visually-hidden">Loading...</span>
                                     </div>

                <div class="table-responsive" v-else>

                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Products</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in cartItems" :key="index">


                                <th scope="row">
                                    <div class="d-flex align-items-center">




                              <img :src="item.productDetails.first_image?.path"  class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" :alt="item.productDetails.first_image?.name">

                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4">{{ item.productDetails?.name  }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">{{ item.productDetails?.price}} OMR</p>
                                </td>
                                <td>
                                    <div class="input-group quantity mt-4" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" @click="decrement(index)">
                                            <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center border-0" :value="item.quantity">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus rounded-circle bg-light border" @click="increment(index,item)">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">{{ item.productDetails?.price * item.quantity }}</p>
                                </td>
                                <td>
                                    <button class="btn btn-md rounded-circle bg-light border mt-4" @click="removeItem(item.productId)">
                                        <i class="fa fa-times text-danger"></i>
                                    </button>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="row g-4 justify-content-end">
                    <div class="col-8"></div>
                    <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                        <div class="bg-light rounded">
                            <div class="p-4">
                                <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                                <div class="d-flex justify-content-between mb-4">
                                    <h5 class="mb-0 me-4">Subtotal:</h5>
                                    <p class="mb-0">{{ totalProductPrice }} OMR</p>
                                </div>


                            </div>
                            <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                                <h5 class="mb-0 ps-4 me-4">Total</h5>
                                <p class="mb-0 pe-4">{{ totalProductPrice }} OMR</p>
                            </div>
                            <a  href="/checkout" class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceed Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cart Page End -->


</template>
<script setup>
import { ref, computed,onMounted } from 'vue';
import { useCartStore } from '../components/cart/cart.js';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
import axios from 'axios';

const cartStore = useCartStore();
const cartItems = ref([]);

const loading = ref(false);




// Function to load cart from localStorage
const loadCartFromLocalStorage = async () => {

const storedCart = localStorage.getItem('cart');
if (storedCart) {
  cartItems.value = JSON.parse(storedCart);
  await fetchProductDetails();


}

loading.value = false;

};


const fetchProductDetails = async () => {

    loading.value = true;
for (let item of cartItems.value) {
  try {
    const response = await axios.get(`/prod/${item.productId}`);
    item.productDetails = response.data; // Assuming response.data contains the product details
    loading.value = false;

  } catch (error) {
    console.error("Error fetching product details:", error);
  loading.value = false;

    // Handle error appropriately
  }
}
};


const updateLocalStorage = () => {
  localStorage.setItem('cart', JSON.stringify(cartItems.value));
};

const increment = (index,item) => {
  if (cartItems.value[index].quantity <  item.productDetails.stock) {
       cartItems.value[index].quantity++;
       updateLocalStorage();
   }
};

const decrement = (index) => {
    if (cartItems.value[index].quantity > 1) {
        cartItems.value[index].quantity--;
        updateLocalStorage();
  }
};



const removeItem =  async (productId) => {
    cartStore.removeFromCart(productId);
    await loadCartFromLocalStorage();
};



const totalProductPrice = computed(() => {
  const total = cartItems.value.reduce((total, item) => {

    if (item.productDetails && item.productDetails.price) {
      return total + (item.productDetails.price * item.quantity);
    }
    return total;

  }, 0);
  return total.toFixed(2); // Format to 2 decimal places
});





// Load the cart data when the component is mounted
onMounted(async() => {
  await loadCartFromLocalStorage();

 console.log(localStorage.getItem('cart'));
});






</script>

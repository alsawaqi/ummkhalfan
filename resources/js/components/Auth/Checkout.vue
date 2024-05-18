<template>
 <!-- Single Page Header start -->
 <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Checkout</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>

                <li class="breadcrumb-item active text-white">Checkout</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


           <!-- Checkout Page Start -->
           <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4">Your Information</h1>
                <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="customerloading"></i>
                    <div class="row g-5" v-else>

                        <div class="col-md-12 col-lg-6 col-xl-7" >
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

                        </div>
                        <div class="col-md-12 col-lg-6 col-xl-5">
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
                                        <tr v-for="(item, index) in cartItems" :key="index">
                                            <th scope="row">
                                                <div class="d-flex align-items-center mt-2">
                                                    <img :src="item.productDetails.first_image?.path" class="img-fluid rounded-circle" style="width: 90px; height: 90px;" :alt="item.productDetails.first_image?.name">
                                                </div>
                                            </th>
                                            <td class="py-5">{{ item.productDetails?.name  }}</td>
                                            <td class="py-5">{{ item.productDetails?.price  }} OMR</td>
                                            <td class="py-5">{{ item.quantity  }} Qty</td>
                                            <td class="py-5">{{ item.productDetails?.price * item.quantity }} OMR</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                            </th>
                                            <td class="py-5"></td>
                                            <td class="py-5"></td>
                                            <td class="py-5">
                                                <p class="mb-0 text-dark py-3">Subtotal</p>
                                            </td>
                                            <td class="py-5">
                                                <div class="py-3 border-bottom border-top">
                                                    <p class="mb-0 text-dark">{{ totalProductPrice  }} OMR</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                            </th>
                                            <td class="py-5">
                                                <p class="mb-0 text-dark py-4">Delivery</p>
                                            </td>
                                            <td colspan="3" class="py-5">
                                                <div class="form-check text-start">
                                                    <input type="checkbox" class="form-check-input bg-primary border-0" id="Shipping-1" name="Shipping-1" value="true" v-model="shipping">
                                                    <label class="form-check-label" for="Shipping-1">Delivery</label>

                                                </div>
                                                <br>
                                                <div v-if="shipping">

                                                <button class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary" @click.prevent="getLocation" :disabled="loadinglocation || isSubmitting">
                                                    <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loadinglocation"></i>   Get Location
                                                </button>
                                                 <br>
                                                 <br>
                                                   <p>Use Mobile Phone For Accurate Location</p>
                                                 <br>

                                                  <p><b>Your State:</b>   {{ location.address?.state }}</p><br>
                                                  <p><b>Your Village:</b> {{ location.address?.village }}</p><br>
                                                  <p><b>Full Address:</b> {{ location.display_name }}</p><br>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                                <div class="col-12">
                                    <div class="form-check text-start my-3">
                                        <input type="radio" class="form-check-input bg-primary border-0" id="Transfer-1" name="Transfer" value="transfer" v-model="payment" :disabled="isSubmitting">
                                        <label class="form-check-label" for="Transfer-1">Direct Bank Transfer</label>
                                    </div>
                                    <p class="text-start text-dark">Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                </div>
                            </div>

                            <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                                <div class="col-12">
                                    <div class="form-check text-start my-3">
                                        <input type="radio" class="form-check-input bg-primary border-0" id="Delivery-1" name="Delivery" value="cod" v-model="payment" :disabled="isSubmitting">
                                        <label class="form-check-label" for="Delivery-1">Cash On Delivery</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                                <button type="button" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary" @click.prevent="submit()" :disabled="isSubmitting">Place Order</button>
                            </div>
                        </div>
                    </div>

            </div>
        </div>


</template>

<script setup>
import { ref, computed,onMounted } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
import axios from 'axios';



    const cartItems = ref([]);
    const loading = ref(false);
    const customerloading = ref(false);
    const customer = ref({});
    const shipping = ref(false);
    const isSubmitting = ref(false);
    const payment = ref('');
    const location = ref('')
    const latitude = ref(null);
    const longitude = ref(null);
    const loadinglocation = ref(false);

    const toast = useToast();


    const handleInput = (event) => {
      const value = event.target.value;
      const numericValue = value.replace(/\D/g, ''); // Remove non-numeric characters
      event.target.value = numericValue.substring(0, 8); // Enforce 8 digit limit
      customer.value.phone = numericValue.substring(0, 8);
    };


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



const totalProductPrice = computed(() => {
  const total = cartItems.value.reduce((total, item) => {

    if (item.productDetails && item.productDetails.price) {
      return total + (item.productDetails.price * item.quantity);
    }
    return total;

  }, 0);
  return total.toFixed(2); // Format to 2 decimal places
});


const getLocation = () => {

    loadinglocation.value = true;

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async (position) => {
          latitude.value = position.coords.latitude;
          longitude.value = position.coords.longitude;
          await reverseGeocode(latitude.value, longitude.value);
        }, (error) => {
          location.value = 'Error fetching location';
          console.error("Error: " + error.message);
          loadinglocation.value = false;

        });
      } else {
        location.value = 'Geolocation is not supported';
        console.error('Geolocation is not supported by this browser.');
         loadinglocation.value = false;

      }
    };

 const reverseGeocode = async (lat, lon) => {
  try {
    const response = await axios.get(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`, {
      baseURL: '', // Override baseURL
      withCredentials: false // Override withCredentials
    });
    location.value = response.data;
    loadinglocation.value = false;

    } catch (error) {
    location.value = 'Error fetching address';
    console.error('Error: ', error);
    loadinglocation.value = false;

   }
 };

const getCustomer = async () =>{

     customerloading.value = true;

      try{

          let response = await axios.get('/customer');
          customer.value = response.data;
          customerloading.value = false;

        console.log(response.data);

      }catch(error){

        customerloading.value = false;


      }



}




const submit = async () => {

    if(customer.value.first_name == null || customer.value.first_name == ""){
        toast.error('First Name can\'t be empty', {
        position: 'top-right',
        duration: 1900,
        dismissible: true
      });

      return;

    }
    if(customer.value.last_name == null || customer.value.last_name == ""){
        toast.error('Last Name can\'t be empty', {
                    position: 'top-right',
                    duration: 1900,
                    dismissible: true
                });
         return;

    }

    if(payment.value == "" || payment.value == null){
         toast.error('Payment can\'t be empty', {
                position: 'top-right',
                duration: 1900,
                dismissible: true
            });
      return;

    }


    isSubmitting.value = true;

    const formData = new FormData();


     if(shipping.value == true){
        formData.append('latitude', latitude.value);
        formData.append('longitude', longitude.value);
      }

        formData.append('sub_total', totalProductPrice.value);
        formData.append('cart', JSON.stringify(cartItems.value));
        formData.append('customer', JSON.stringify(customer.value));
        formData.append('payment', payment.value);


    try{
            await axios.post('/orders', formData);
            localStorage.clear();
            window.location.assign("https://umkhalfan.com/myorder");




    }catch(error){



          toast.error(error.response.data.message, {
                position: 'top-right',
                duration: 1900,
                dismissible: true
            });

            isSubmitting.value = false;


    }



}


onMounted(async() => {
  await loadCartFromLocalStorage();
  await getCustomer();
  console.log(localStorage.getItem('cart'));

});


</script>

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

                                    <div class="table-responsive" v-else>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">ORDER NUMBER</th>
                                            <th scope="col">PAYMENT TYPE</th>
                                            <th scope="col">TOTAL</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">SHOW</th>
                                            <th scope="col">DATE</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="order in orders" :key="order.id">

                                                <td>
                                                    <p class="mb-0 mt-4"><router-link :to="{ name: 'ordersdetail', params: {orderref: order.reference}}">{{ order.reference }}</router-link></p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 mt-4">{{ order.payment_type =='cod' ? 'Cash on Delivery' : order.payment_type =='transfer' ? 'Bank Transfer' : 'Transfer' }}</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 mt-4">{{ order.total_amount }} OMR</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 mt-4">{{ order.status }}</p>

                                                </td>
                                                <td>
                                                    <p class="mb-0 mt-4"><router-link :to="{ name: 'ordersdetail', params: {orderref: order.reference}}">Show</router-link></p>

                                                </td>
                                                <td>
                                                    <p class="mb-0 mt-4">{{moment(order.created_at).format("MMM DD YYYY [,] HH:mm") }}</p>
                                                </td>


                                            </tr>

                                        </tbody>
                                    </table>
                    </div>

                                <div class="col-12" v-if="pagination.last_page > 1">

                                <div class="pagination d-flex justify-content-center mt-5">
                                    <a @click.prevent="changePage(1)" class="rounded">&laquo;</a>
                                    <a href="" v-for="page in pagination.last_page" :key="page" :class="['rounded', { 'active': page === pagination.current_page }]"  @click.prevent="changePage(page)">
                                            {{ page }}
                                    </a>

                                    <a href="#" class="rounded"  @click.prevent="changePage(pagination.last_page)">&raquo;</a>
                                </div>
                                </div>
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
import { ref ,onMounted } from 'vue';
import NavBar from './Navbar.vue';
import { RouterLink } from 'vue-router';
import { useRoute, useRouter } from 'vue-router';
import moment from 'moment-timezone';

const orders = ref([]);
const pagination = ref({});
const loading = ref(true);
const route = useRoute();
const router = useRouter();



const getorders = async (page = route.query.page || 1) => {
 loading.value = true;

    try{
        const response = await axios.get(`/orders?page=${page}`);
        orders.value = response.data.data.data;
        pagination.value = response.data.data;
        loading.value = false;

     }catch(error){
        loading.value = false;

    }
}


const changePage = async (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    router.push({ path: `/myorder`, query: { page: page } });
        await getorders(page);
   }
};


onMounted(async ()=>{
    await getorders();
})

</script>

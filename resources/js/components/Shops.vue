<template>


     <!-- Single Page Header start -->
     <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Shop</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><NProgressLink href="/">Home</NProgressLink></li>

                <li class="breadcrumb-item active text-white">Shop</li>
            </ol>
        </div>
        <!-- Single Page Header End -->




        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">Fresh fruits shop</h1>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <div class="col-xl-3" style="margin-bottom: 20px;">
                                <div class="input-group w-100 mx-auto d-flex">
                                    <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <div class="col-6"></div>
                            <div class="col-xl-3">

                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-3">
                                <div class="row g-4">


                                    <div class="col-lg-12">
                                        <div class="position-relative">
                                            <img src="img/banner-fruits.jpg" class="img-fluid w-100 rounded" alt="">
                                            <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="row g-4 justify-content-center">
                                    <div class="spinner-border text-primary text-center" role="status" v-if="loading">
                                      <span class="visually-hidden">Loading...</span>
                                     </div>

                                    <div class="col-md-6 col-lg-6 col-xl-4"  v-for="product in products" :key="product.id" v-else>
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img :src="product.first_image?.path" class="img-fluid w-100 rounded-top" :alt="product.first_image?.name">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">{{ product.categories?.name }}</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>{{ product.name }}</h4>

                                                <div class="d-flex justify-content-between flex-lg-wrap">
                                                    <p class="text-dark fs-5 fw-bold mb-0">OMR {{ product.price }}</p>
                                                    <NProgressLink :href="'/prouducts/'+ product.slug" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</NProgressLink>
                                                </div>
                                            </div>
                                        </div>
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
import { ref , onMounted} from 'vue';
import { useRoute, useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();


import NProgressLink from '../components/inc/NProgressLink.vue';


const pagination = ref({});
const loading = ref(true);
const products = ref([]);


const getProducts = async (page = route.query.page || 1) =>{

 loading.value = true;

    try {

    const response = await axios.get(`/product?page=${page}`);

    products.value = response.data.data.data;
    pagination.value = response.data.data;
    loading.value = false;

    console.log(response.data.data.data);
   } catch (error) {

    loading.value = false;
    console.error('Error fetching data:', error);


  }

}

const changePage = async (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    router.push({ path: `/prouducts`, query: { page: page } });
        await getProducts(page);
   }
};


onMounted(async()=>{
      await getProducts();
})
</script>

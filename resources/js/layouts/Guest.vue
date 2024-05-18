<template>
     <Head></Head>
     <RouterView />
     <Footer></Footer>

</template>
<script setup>
import { RouterView  } from 'vue-router';
import Head from '../components/inc/head.vue';
import Footer from '../components/inc/Footer.vue';
import { fetchCustomer, customer } from '../composable/useCustomer.js';
import { onMounted, provide } from 'vue';

onMounted(() => {
    fetchCustomer();
});



provide('customerData', customer);


</script>

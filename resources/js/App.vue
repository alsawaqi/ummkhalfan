<template>
    <RouterView />
</template>
<script setup>
 import { RouterView } from 'vue-router';
</script>


<style>

#nprogress .bar {
    height: 5px; /* Change this value to adjust the thickness */
    background: #01bab4; /* You can also change the color here */
}

/* Custom styles for NProgress spinner */
#nprogress .spinner {
    display: block; /* Ensure the spinner is displayed */
    top: 15px; /* Position from the top */
    right: 15px; /* Position from the right */
}

#nprogress .spinner-icon {
    width: 18px; /* Width of the spinner */
    height: 18px; /* Height of the spinner */
    border: solid 2px transparent; /* Border properties */
    border-top-color: #01bab4; /* Color of the spinning part */
    border-left-color: #01bab4; /* Color of the spinning part */
    border-radius: 50%; /* Makes it circular */
    -webkit-animation: nprogress-spinner 400ms linear infinite; /* Animation for spinning (for WebKit browsers) */
    animation: nprogress-spinner 400ms linear infinite; /* Animation for spinning */
}


@-webkit-keyframes nprogress-spinner {
    0%   { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}
@keyframes nprogress-spinner {
    0%   { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>

<template>
    <a :href="href" @click="handleClick">
      <slot></slot> <!-- allows for content to be passed into the component -->
    </a>
  </template>

  <script setup>
  import { defineProps, onMounted, onUnmounted } from 'vue';

  import { startProgress, endProgress } from  './NprogressWrapper.js';

  const props = defineProps({
  href: {
    type: String,
    required: true
  }
});

const handleClick = (event) => {
  event.preventDefault();
  startProgress();
  setTimeout(() => {
    window.location.href = props.href;
    endProgress();
  }, 100); // Slight delay to start the navigation
};
  </script>

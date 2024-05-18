<template>

<div class="col-lg-3">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="mb-3">

                                            <ul class="list-unstyled fruite-categorie">
                                                <li>
                                                    <div class="d-flex justify-content-between fruite-name">
                                                        <router-link  :to="{ name: 'order'}">My Orders</router-link>

                                                    </div>
                                                </li>
                                                <hr>
                                                <li>
                                                    <div class="d-flex justify-content-between fruite-name">
                                                        <router-link :to="{ name: 'account'}">Account</router-link>

                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>




                                </div>
                            </div>

</template>
<script setup>

import { RouterLink } from 'vue-router';




</script>

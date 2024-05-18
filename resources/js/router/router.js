import { createRouter,createWebHistory } from "vue-router";
import { useAuthStore } from '../auth/auth.js';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';


function requireAuth(to, from, next) {
    const authStore = useAuthStore();
    if (!authStore.isAuthenticated) {
        next({ name: 'Login' });
    } else {
        next();
    }
}

function requireGuest(to, from, next) {
    const authStore = useAuthStore();
    if (authStore.isAuthenticated) {
        next({ name: 'Home' });
    } else {
        next();
    }
}



function requireAuthAndNonEmptyCart(to, from, next) {
    const authStore = useAuthStore();
    const cart = localStorage.getItem('cart');
    const isCartNotEmpty = cart && JSON.parse(cart).length > 0;

    if (!authStore.isAuthenticated) {
      next({ name: 'Login' }); // Redirect to login if not authenticated
    } else if (!isCartNotEmpty) {
      next({ name: 'Home' }); // Redirect to home if cart is empty
    } else {
      next(); // Proceed if authenticated and cart is not empty
    }
}


const routes = [
                 {
                    path: '/',
                    component : () => import('../layouts/Guest.vue'),
                    children: [

                        {
                            path: "/:catchAll(.*)*",
                            component : () => import('../components/error/NotFound.vue'),
                            name: 'error',
                             meta: {
                                title: 'Error',
                            },

                           },


                        {
                            path: '/',
                            name: 'Home',

                            component: () => import('../components/Home.vue'),
                            meta: {
                                title: 'Home',
                            },
                        },

                        {
                            path: '/prouducts',
                            name: 'Shops',

                            component: () => import('../components/Shops.vue'),
                            meta: {
                                title: 'Shops',
                            },
                        },



                        {
                            path: '/prouducts/:id',
                            name: 'ShopsDetail',
                            props:true,

                            component: () => import('../components/ShopDetail.vue'),
                            meta: {
                                title: 'Shops',
                            },
                        },

                        {
                            path: '/login',
                            name: 'Login',
                            component: () => import('../components/Auth/Login.vue'),
                            meta: {
                                title: 'Login',
                            },
                            beforeEnter: requireGuest,
                        },

                        {
                            path: '/register',
                            name: 'Register',
                            component: () => import('../components/Auth/Register.vue'),
                            meta: {
                                title: 'Register',
                            },
                            beforeEnter: requireGuest,
                        },


                        {
                            path: '/cart',
                            name: 'cart',
                            component: () => import('../components/Cart.vue'),
                            meta: {
                                title: 'cart',
                            },
                        },

                        {
                            path: '/contact-us',
                            name: 'contact',
                            component: () => import('../components/Contact.vue'),
                            meta: {
                                title: 'Contact Us',
                            },


                         },
                        {
                            path: '/myorder',
                            name: 'order',
                            component: () => import('../components/auth/Orders.vue'),
                            meta: {
                              title: 'My Orders',
                            },
                            beforeEnter: requireAuth, // Apply the new guard here
                          },

                          {
                            path: '/myaccount',
                            name: 'account',
                            component: () => import('../components/auth/Account.vue'),
                            meta: {
                              title: 'My Account',
                            },
                            beforeEnter: requireAuth, // Apply the new guard here
                          },

                          {
                            path: '/myorder/:orderref',
                            name: 'ordersdetail',
                            props: true,
                            component: () => import('../components/auth/OrdersDetails.vue'),
                            meta: {
                              title: 'My Orders List',
                            },
                            beforeEnter: requireAuth, // Apply the new guard here
                          },


                        {
                            path: '/checkout',
                            name: 'checkout',
                            component: () => import('../components/auth/Checkout.vue'),
                            meta: {
                              title: 'Check Out',
                            },
                            beforeEnter: requireAuthAndNonEmptyCart, // Apply the new guard here
                          },

                    ]
                 },


             ];

   const router = createRouter({
        history: createWebHistory(),
        routes,
        scrollBehavior(to, from, savedPosition) {
            // Always scroll to top
            return { top: 0 };
          },
     })

router.beforeEach((to,from, next)=>{
    NProgress.start();
    const id = to.params.id;

    if (id) {
        const formattedTitle = id.replace(/-/g, ' ');
        document.title = formattedTitle;
     }else{
        document.title = `${to.meta.title}`;
      }

    next();
});



// Stop NProgress after each route change
router.afterEach(() => {
    NProgress.done();


    try {
        if (typeof window.someGlobalFunction === 'function') {
            window.someGlobalFunction();
        }
    } catch (error) {
        console.error('Error executing someGlobalFunction:', error);
    }


  window.scrollTo(0, 0);
});

// Optional: Handle route change errors
router.onError((error) => {
    console.error(error);
    NProgress.done();
});


export default router;

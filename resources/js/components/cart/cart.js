import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
  state: () => ({
    cartItems: []
  }),
  getters: {
    totalProductPrice: (state) => {
        return state.cartItems.reduce((total, item) => {
          return total + (item.productDetails.price * item.quantity);
        }, 0).toFixed(2);
      },
      vat: (state, getters) => {
        const vatPercentage = 0.05; // 5%
        return (parseFloat(getters.totalProductPrice) * vatPercentage).toFixed(2);
      },
      grandTotal: (state, getters) => {
        return (parseFloat(getters.totalProductPrice) + parseFloat(getters.vat)).toFixed(2);
      },
    numberOfCartItems: (state) => state.cartItems.length
  },
  actions: {

    async loadCartFromLocalStorage() {
        const storedCart = localStorage.getItem('cart');
        if (storedCart) {
          this.cartItems = JSON.parse(storedCart);
          await this.fetchProductDetails();
        }
      },
      async fetchProductDetails() {
        for (let item of this.cartItems) {
          try {
            const response = await axios.get(`/prod/${item.productId}`);
            item.productDetails = response.data;
          } catch (error) {
            console.error("Error fetching product details:", error);
          }
        }
      },
      async removeFromCart(productId) {
        this.cartItems = this.cartItems.filter(item => item.productId !== productId);
        localStorage.setItem('cart', JSON.stringify(this.cartItems));
      },
      saveCartToLocalStorage() {
        localStorage.setItem('cart', JSON.stringify(this.cartItems));
      },

    addToCart(product, quantity) {
      const existingCartItemIndex = this.cartItems.findIndex(item => item.productId === product.id);
      if (existingCartItemIndex !== -1) {
        this.cartItems[existingCartItemIndex].quantity = quantity;
        } else {
        this.cartItems.push({ productId: product.id, quantity });
      }
      localStorage.setItem('cart', JSON.stringify(this.cartItems));
    },
     removeFromCart(productId) {
      this.cartItems = this.cartItems.filter(item => item.productId !== productId);
      localStorage.setItem('cart', JSON.stringify(this.cartItems));
    },
  }
});

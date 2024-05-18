// useCustomer.js
import { ref  } from 'vue';
import axios from 'axios';

const customer = ref({});

const fetchCustomer = async () => {
    try {
        let response = await axios.get('/api/check-auth');

        customer.value = response.data.user;

    } catch (e) {
        // Handle error
        console.error(e);
    }
};

export { fetchCustomer, customer };


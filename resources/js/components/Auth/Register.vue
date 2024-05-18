<template>
 <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Register</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>

                <li class="breadcrumb-item active text-white">Register</li>
            </ol>
        </div>


        <!-- Contact Start -->
        <div class="container-fluid contact py-5">




            <div class="container py-5">
                <div v-if="errors">
                        <div v-for="(errorMessages, field) in errors" :key="field">
                        <p class="text-danger mb-1" v-for="errorMessage in errorMessages" :key="errorMessage">
                            {{ errorMessage }}
                        </p>
                        </div>
                    </div>


                    <p class="text-danger mb-1" v-if="errorphone">
                            {{ errorphone }}
                        </p>



                <div class="p-5 bg-light rounded">


                    <div class="row g-4">


                        <div class="col-lg-12">
                            <form @submit.prevent="register">
                                <input type="text" class="w-100 form-control border-0 py-3 mb-4" placeholder="Enter Your User Name" v-model="name" @input="validateUsername" required>
                                   <p v-if="errorMessages">
                                    <span class="text-danger">{{ errorMessages }}</span>
                                    </p>


                                <input type="email" class="w-100 form-control border-0 py-3 mb-4" placeholder="Enter Your Email" v-model="email" required>

                                <input type="text" class="w-100 form-control border-0 py-3 mb-4" placeholder="Enter Your Phone Number" :value="phone" @input="handleInput" required>

                                <input :type="passwordInputType" class="w-100 form-control border-0 py-3 mb-4" placeholder="Enter Your Password" v-model="password"  @input="validatePasswords" required>
                                <button type="button" class="toggle-password" @click="togglePasswordVisibility">{{ showPassword ? 'Hide' : 'Show' }}</button>


                                <input :type="passwordConfirmInputType" class="w-100 form-control border-0 py-3 mb-4" placeholder="Enter Your Password Confirmation" v-model="password_confirmation" @input="validatePasswords" required>

                                <button type="button" class="toggle-password" @click="togglePasswordConfirmVisibility">{{ showPasswordConfirm ? 'Hide' : 'Show' }}</button>

                                   <p>
                                    <span class="text-danger">{{ errorPhoneMessage }}</span>
                                    </p>


                                 <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary " type="submit" :disabled="!isFormValid || isSubmitted">
                                    <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="isSubmitted"></i>

                                    Register

                                </button>
                            </form>



                        </div>



                    </div>
                </div>
            </div>
               </div>
               <!-- Contact End -->
</template>
<script setup>
 import { computed ,ref  } from 'vue';
 import { useAuthStore } from '../../auth/auth.js';

 import { useToast } from 'vue-toast-notification';
 import 'vue-toast-notification/dist/theme-default.css';

const toast = useToast();
const authStore = useAuthStore();




 const name = ref('');
 const email = ref('');
 const phone = ref('');
 const password = ref('');
 const password_confirmation = ref('');
 const errors = ref('');
 const errorphone = ref('');
 const errorMessages = ref('');
 const isSubmitted = ref(false);
 const errorPhoneMessage = ref('');
 const showPassword = ref(false);
 const showPasswordConfirm = ref(false);

 const handleInput = (event) => {
  const value = event.target.value;
  const numericValue = value.replace(/\D/g, ''); // Remove non-numeric characters
  event.target.value = numericValue.substring(0, 8); // Enforce 8 digit limit
  phone.value = numericValue.substring(0, 8);
};


const isValidUsername = computed(() => /^[a-zA-Z0-9]{0,13}$/.test(name.value));

const validateUsername = (event) => {
  const value = event.target.value.replace(/\s/g, ''); // Remove spaces
  name.value = value.substring(0, 13); // Limit to 10 characters and remove spaces

  if (!isValidUsername.value && value) {
    errorMessages.value = 'Username must be alphanumeric and up to 10 characters long.';
  } else {
    errorMessages.value = '';
  }
};
const isPasswordValid = computed(() => {
  const minLength = 8;
  const hasUppercase = /[A-Z]/.test(password.value);
  const hasLowercase = /[a-z]/.test(password.value);
  const hasNumbers = /\d/.test(password.value);
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password.value);

  return password.value.length >= minLength && hasUppercase && hasLowercase && hasNumbers && hasSpecialChar;
});

const passwordsDontMatch = computed(() => password.value !== password_confirmation.value);

const validatePasswords = () => {
  if (!isPasswordValid.value) {
    errorPhoneMessage.value = 'Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.';
  } else if (passwordsDontMatch.value) {
    errorPhoneMessage.value = 'Passwords do not match.';
  } else {
    errorPhoneMessage.value = '';
  }
};

const passwordInputType = computed(() => showPassword.value ? 'text' : 'password');
const passwordConfirmInputType = computed(() => showPasswordConfirm.value ? 'text' : 'password');


const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};



const togglePasswordConfirmVisibility = () => {
    showPasswordConfirm.value = !showPasswordConfirm.value;
};



const isFormValid = computed(() => {
  // Ensure all validations are true
  return isValidUsername.value && phone.value.length === 8 && isPasswordValid.value && !passwordsDontMatch.value && !errorMessages.value && !errorPhoneMessage.value;
});


 const register = async () => {


            if (!isFormValid.value) {
            // If the form is not valid, show an error message or stop the function
            toast.error('Please correct the errors before submitting.', {
            position: 'top-right',
            duration: 1900,
            dismissible: true
            });
            return;
        }
        isSubmitted.value = true;

        try{

            await authStore.register({
                        name: name.value,
                        email: email.value,
                        phone: phone.value,
                        password: password.value,
                        password_confirmation: password_confirmation.value
                    });

        }catch (e){


            if (e.response.status === 422) {

                errors.value = e.response.data.validation;
                isSubmitted.value = false;


                errorphone.value = e.response.data.error;

                }

               const content = '<i class="fa fa-window-close" aria-hidden="true"></i> something went wrong';

                    toast.error(content, {
                        position: 'top-right',
                        duration: 1900,
                        dismissible: true
                    });

                } finally {
                    isSubmitted.value = false;
                    }

            };


</script>
<style>
.input-wrapper {
  position: relative;
  margin-bottom: 1rem;
}

.password-input {
  width: 100%;
  padding-right: 2em;
  /* Add other input styling here */
}

.toggle-password {
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
}

.error-message {
  color: red;
  /* Additional styling */
}

/* Responsive adjustments */
@media (max-width: 600px) {
  .toggle-password {
    right: 3%; /* Adjust positioning for smaller screens */
  }
}
</style>

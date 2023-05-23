<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-md p-6 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-4">OTP Verification</h1>

      <div v-if="!isVerified">
        <p>Please enter the OTP sent to your email:</p>
        <input type="text" v-model="otp" class="w-full py-2 px-4 mb-4 border rounded" />
        <button @click="verifyOtp" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Verify
        </button>
      </div>

      <div v-else>
        <p>OTP verified successfully!</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      otp: '',
      isVerified: false,
    };
  },
  methods: {
    verifyOtp() {
      // Send a request to the server to verify the OTP
      this.$inertia.post('/verify-otp', { otp: this.otp })
        .then(response => {
          // OTP verification successful
          this.isVerified = true;

          // Show a success notification
          this.$toast.success('OTP verified successfully');
        })
        .catch(error => {
          // OTP verification failed
          // Display the error message returned by the server
          this.$toast.error(error.response.data.message);
        });
    },
  },
};
</script>

<style>
/* Add your custom styles here */
</style>

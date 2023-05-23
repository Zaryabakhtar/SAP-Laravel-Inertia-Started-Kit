<template>
    <div class="flex flex-col items-center justify-center h-screen">
      <div class="max-w-sm mx-auto mt-8 p-4 bg-white shadow">
        <h2 class="text-2xl font-bold mb-4">Welcome to the Questionnaire!</h2>
        <p class="mb-4">Click the button below to start the questionnaire.</p>
        <div class="text-center">
            <Link
                :href="questionnaireLink"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                Start Questionnaire
            </Link>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/inertia-vue3';
  
  export default {
    components: {
      Link,
    },
    computed: {
      questionnaireLink() {
        return '/questionnaire'; // Replace with the actual route for your questionnaire
      },
    },
  };
  </script>
  
  <style>
  /* Add any custom styling here */
  </style>
  
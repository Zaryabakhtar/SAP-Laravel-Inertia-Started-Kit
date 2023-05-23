<template>
  <div class="min-h-screen bg-gray-100">
    <div class="flex h-full">
      <div class="flex-1 flex flex-col">
        <!-- Navbar -->
        <nav class="flex items-center justify-between bg-blue-500 px-4 py-2">
          <div class="flex items-center space-x-2">
            <!-- Navtabs -->
            <div class="flex items-center space-x-4 text-white">
              <button
                :class="{'bg-blue-600': activeTab === 'tab1'}"
                @click="activeTab = 'tab1'"
                class="px-3 py-2 rounded hover:bg-blue-600 transition-colors duration-300"
              >
                <svg
                  class="h-5 w-5 inline-block"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                >
                  <!-- Icon for Tab 1 -->
                </svg>
                <span>Tab 1</span>
              </button>
              <button
                :class="{'bg-blue-600': activeTab === 'tab2'}"
                @click="activeTab = 'tab2'"
                class="px-3 py-2 rounded hover:bg-blue-600 transition-colors duration-300"
              >
                <svg
                  class="h-5 w-5 inline-block"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                >
                  <!-- Icon for Tab 2 -->
                </svg>
                <span>Tab 2</span>
              </button>
              <button
                :class="{'bg-blue-600': activeTab === 'tab3'}"
                @click="activeTab = 'tab3'"
                class="px-3 py-2 rounded hover:bg-blue-600 transition-colors duration-300"
              >
                <svg
                  class="h-5 w-5 inline-block"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                >
                  <!-- Icon for Tab 3 -->
                </svg>
                <span>Tab 3</span>
              </button>
            </div>
          </div>

          <!-- Icons -->
          <div class="flex items-center space-x-2">
            <svg
              class="h-5 w-5 text-white cursor-pointer hover:text-gray-200 transition-colors duration-300"
              viewBox="0 0 20 20"
              fill="currentColor"
              aria-hidden="true"
            >
              <!-- First icon -->
            </svg>
            <svg
              class="h-5 w-5 text-white cursor-pointer hover:text-gray-200 transition-colors duration-300"
              viewBox="0 0 20 20"
              fill="currentColor"
              aria-hidden="true"
            >
              <!-- Second icon -->
            </svg>
          </div>

          <!-- User Dropdown -->
          <div class="relative">
    <button @click="toggleDropdown" class="flex items-center space-x-1 focus:outline-none">
      <span>{{ loggedInUser.name }}</span>
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M10 12a2 2 0 100-4 2 2 0 000 4z"
        ></path>
      </svg>
    </button>

    <div v-if="isDropdownOpen" @click="closeDropdown" class="fixed inset-0 h-screen w-screen z-10"></div>

    <div v-if="isDropdownOpen" class="absolute right-0 mt-2 bg-white text-gray-800 rounded shadow-md">
      <ul>
        <li>
          <a href="#" class="block px-4 py-2 hover:bg-gray-300">Profile</a>
        </li>
        <li>
          <a href="#" class="block px-4 py-2 hover:bg-gray-300">Settings</a>
        </li>
        <li>
          <button @click="logout" class="block w-full text-left px-4 py-2 hover:bg-gray-300">Logout</button>
        </li>
      </ul>
    </div>
  </div>
        </nav>

        <!-- Tab Content -->
        <div class="flex-1 overflow-y-auto p-4">
          <div v-show="activeTab === 'tab1'">
            <table class="min-w-full bg-white border border-gray-200">
              <thead>
                <tr>
                  <th class="px-6 py-3 border-b">Name</th>
                  <th class="px-6 py-3 border-b">ID</th>
                  <th class="px-6 py-3 border-b">Question 1</th>
                  <th class="px-6 py-3 border-b">Question 2</th>
                  <th class="px-6 py-3 border-b">Question 3</th>
                  <th class="px-6 py-3 border-b">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="answer in answers" :key="answer.id">
  <td class="px-6 py-4 whitespace-nowrap">{{ answer.user.name }}</td>
  <td class="px-6 py-4 whitespace-nowrap">{{ answer.id }}</td>
  <td class="px-6 py-4 whitespace-nowrap">
    <input v-model="answer.question_1" :disabled="!answer.editable" class="border border-gray-300 px-2 py-1 rounded">
  </td>
  <td class="px-6 py-4 whitespace-nowrap">
    <input v-model="answer.question_2" :disabled="!answer.editable" class="border border-gray-300 px-2 py-1 rounded">
  </td>
  <td class="px-6 py-4 whitespace-nowrap">
    <input v-model="answer.question_3" :disabled="!answer.editable" class="border border-gray-300 px-2 py-1 rounded">
  </td>
  <td class="px-6 py-4 whitespace-nowrap">
    <button v-if="!answer.editable" @click="editAnswer(answer)" class="flex items-center space-x-1 bg-yellow-500 hover:text-yellow-700">
      <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <!-- Edit icon -->
      </svg>
      <span>Edit</span>
    </button>
    <button v-else @click="updateAnswer(answer)" class="flex items-center space-x-1 bg-green-500 hover:text-green-700">
      <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <!-- Update icon -->
      </svg>
      <span>Update</span>
    </button>
    <!-- Delete button -->
    <button @click="deleteAnswer(answer)" class="flex items-center space-x-1 bg-red-500 hover:text-red-700">
      <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <!-- Delete icon -->
      </svg>
      <span>Delete</span>
    </button>
  </td>
</tr>

              </tbody>
            </table>
          </div>

          <div v-show="activeTab === 'tab2'">
            <!-- Content for Tab 2 -->
          </div>

          <div v-show="activeTab === 'tab3'">
            <!-- Content for Tab 3 -->
          </div>
        </div>
      </div>
    </div>
    <!-- Overlay -->
    <div class="fixed bottom-0 right-0 m-4">
      <button @click="showPopup" class="bg-blue-500 rounded-full p-4 text-white">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path
            fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12zm-2-6a1 1 0 112 0v2a1 1 0 11-2 0v-2zm2-6a1 1 0 00-1 1v2a1 1 0 102 0V5a1 1 0 00-1-1z"
          ></path>
        </svg>
      </button>
    </div>
    <!-- Popup Form -->
    <div v-show="isPopupVisible" class="fixed inset-0 flex items-center justify-center">
      <div class="bg-white p-6 rounded shadow-md w-screen h-screen">
        <!-- Cancel Icon -->
        <button @click="hidePopup" class="absolute top-4 right-4">
          <svg class="h-6 w-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path
              fill-rule="evenodd"
              d="M10 11.414l4.95 4.95a1 1 0 101.414-1.414L11.414 10l4.95-4.95a1 1 0 10-1.414-1.414L10 8.586 5.05 3.636A1 1 0 103.636 5.05L8.586 10l-4.95 4.95a1 1 0 101.414 1.414L10 11.414z"
            ></path>
          </svg>
        </button>

        <!-- Form content -->
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import Sidebar from '../Shared/Sidebar.vue';

export default {
  components: {
    Link,
    Sidebar,
  },
  props: {
  answers: Array,
},
data() {
  return {
    activeTab: 'tab1',
    // Existing data properties
    isPopupVisible: false,
    loggedInUser: {
      name: 'John Doe', // Replace with the actual logged-in user's name
    },
    isDropdownOpen: false,
    // Remove the duplicate declaration of 'answers' here
  };
},
  methods: {
    // Method to delete an answer
  deleteAnswer(answer) {
    // Perform the delete logic (e.g., make an API request to delete the answer from the database)
    // You can use your preferred method to delete the answer, such as Axios or Inertia.js

    // Example using Inertia.js
    this.$inertia.delete(`/questionnaire/delete/${answer.id}`)
      .then(() => {
        // Remove the answer from the answers array
        const index = this.answers.findIndex((a) => a.id === answer.id);
        if (index !== -1) {
          this.answers.splice(index, 1);
        }
        // Show a success notification
        Toast.show('Answer deleted successfully', { type: 'success' });
      })
      .catch((error) => {
        // Handle the error and show an error notification
        console.error(error);
        Toast.show('Failed to delete answer', { type: 'error' });
      });
  },
    // Method to enable editing mode for an answer
  editAnswer(answer) {
    answer.editable = true;
  },
  // Method to update an answer
  updateAnswer(answer) {
  this.$inertia.put(`/questionnaire/update/${answer.id}`, {
    _method: 'PUT', // Add this line to specify the PUT method
    question_1: answer.question_1,
    question_2: answer.question_2,
    question_3: answer.question_3,
  })
  .then(() => {
    // Rest of your code
  })
  .catch((error) => {
    // Rest of your code
  });
},
    showPopup() {
      this.isPopupVisible = true;
    },
    hidePopup() {
      this.isPopupVisible = false;
    },
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen;
    },
    closeDropdown() {
      this.isDropdownOpen = false;
    },
    logout() {
      // Perform the logout logic here
      // Redirect or perform any necessary actions
      // For example, you can use the Inertia.js logout method:
      this.$inertia.post('/logout');
    },
  },
};
</script>

<style>
html,
body {
  height: 100vh;
}

</style>

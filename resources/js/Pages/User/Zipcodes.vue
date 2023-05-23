<template>
    <div>
      <Sidebar />
    </div>
    <div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Zipcode</th>
            <th>State</th>
            <th>Code</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="zipcode in zipCodes" :key="zipcode.id" :class="{ 'purple-row': isZipCodeMatched(zipcode.zipcode) }">
            <td>{{ zipcode.id }}</td>
            <td>{{ zipcode.zipcode }}</td>
            <td>{{ zipcode.state_abbr }}</td>
            <td class="unique_code">{{ zipcode.unique_code }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/inertia-vue3';
  import Sidebar from '../Shared/Sidebar.vue';
  import Echo from 'laravel-echo';
  
  export default {
    components: {
      Link,
      Sidebar,
    },
    props: {
      zipCodes: Object,
      matched: Object,
    },
    mounted() {
      this.setupPusher();
    },
    methods: {
      setupPusher() {
        var echo = new Echo({
        broadcaster: 'pusher',
        key: 'ea434f0cdc68ccff572b',
        cluster: 'ap2',
        // Add any other Pusher options you need
      });
      echo.channel('zipcode-updated').listen('ZipcodeUpdated', (response) => {
        // Handle the received data
        this.applyHighlight(response.data);
      });
    },
      updateZipCode(updatedZipCode) {
        const index = this.zipCodes.findIndex((zipcode) => zipcode.id === updatedZipCode.id);
        if (index !== -1) {
          this.zipCodes.splice(index, 1, updatedZipCode);
          this.$forceUpdate(); // Force re-render to apply CSS class changes
        } else {
          this.zipCodes.push(updatedZipCode);
        }
      },
      isZipCodeMatched(zipcode) {
      return this.matched && this.matched.includes(zipcode);
    },
    applyHighlight(answer) {
      const rows = document.querySelectorAll('tr');
      rows.forEach((row) => {
        const uniqueCode = row.querySelector('.unique_code');
        if (uniqueCode && uniqueCode.textContent == answer) {
          row.classList.add('purple-row');
        } else {
          // row.classList.remove('purple-row');
        }
      });
    },
      isMatchedInList(answer) {
        const rows = document.querySelectorAll('.unique_code');
        rows.forEach((row) => {
            if (row.textContent == answer) {
            row.parentNode.classList.add('purple-row');
            }
        });
      },
    },
  };
  </script>
  
  <style>
  .purple-row {
    background-color: purple;
    color: white;
  }
  </style>
  
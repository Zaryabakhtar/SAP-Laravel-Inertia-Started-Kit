<template>
    <aside class="sidebar">
      <!-- Sidebar content -->
        <div>
            Sidebar
        </div>
        <ul>
            <li>
                <Link href="/user">Answers</Link>
            </li>
            <li>
                <Link href="/user/charts">Charts</Link>
            </li>
            <li>
                <Link href="/user/zipcodes">Zipcodes</Link>
            </li>
        </ul>
    </aside>
  </template>
  
  <script>
    import { Link } from '@inertiajs/inertia-vue3';
  export default {
    
    data() {
      return {
        menuItems: [
          { id: 1, title: 'Home', isActive: false },
          { id: 2, title: 'About', isActive: false },
          { id: 3, title: 'Services', isActive: false },
          { id: 4, title: 'Contact', isActive: false },
        ],
      };
    },
    components: {
    Link
    },
    methods: {
      handleItemClick(item) {
        // Set the clicked menu item as active
        this.menuItems.forEach(menuItem => {
          menuItem.isActive = menuItem.id === item.id;
        });
  
        // Emit an event or perform any other necessary action
        // For example, you can emit an event to notify the parent component about the menu item selection
        this.$emit('menu-item-selected', item);
      },
    },
  };
  </script>
  
  <style>
  </style>
  
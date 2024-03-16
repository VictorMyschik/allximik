require('./bootstrap');

import { createApp } from 'vue';
import main_page from './components/main_page.vue';
import nav_bar from './components/nav_bar.vue';

createApp({
  components: {
    main_page,
    nav_bar,
  }
}).mount('#app');




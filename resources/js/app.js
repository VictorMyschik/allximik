require('./bootstrap');

import { createApp } from 'vue';
import main_page from './components/main_page.vue';

createApp({
  components: {
    main_page,
  }
}).mount('#app');




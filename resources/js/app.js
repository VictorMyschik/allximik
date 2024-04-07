require('./bootstrap');

import {createApp} from 'vue';
import mrp from './components/MrPopupForm.vue';
import main_page from './components/main_page.vue';
import nav_bar from './components/nav_bar.vue';
import account_travel_list from './components/account/travel/page.vue';

createApp({
  components: {
    mrp,
    main_page,
    nav_bar,
    account_travel_list,
  }
}).mount('#app');




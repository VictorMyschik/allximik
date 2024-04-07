<script>
export default {
  name: "nav_bar",
  data() {
    return {
      urlList: {
        "api.travel.list": "/api/travel/list",
        "account.travel.page": "/account/travel/{travel_id}/page",
      },
      list: null,
    }
  },
  created() {
    this.getUsersTravelList();
  },

  methods: {
    getUsersTravelList: function () {
      axios.post(this.urlList['api.travel.list']).then(response => {
          if (response.data.result !== true) {
            console.log('Error');
            return;
          }

          this.list = response.data.content;
        }
      );
    },

    buildLink: function (url, id) {
      return url.replace('{travel_id}', id);
    }
  }
}
</script>

<template>
  <div class="dropdown-menu dropdown-menu-right mr-nav-link-submenu-background"
       aria-labelledby="navbarDropdown">
    <a class="nav-link" v-for="item in this.list"
       :href="buildLink(this.urlList['account.travel.page'], item.id)">{{ item.name }}</a>
  </div>
</template>

<style scoped>

</style>

<template>
  <div class="row col-md-12 col-sm-12 col-lg-12">
    <div class="col-md-2 p-3 mr-left-side" style="border-radius: 5px;">
      <h4>Описание</h4>
      <h4>Участники</h4>
      <h4>Снаряжение</h4>
      <h4>Питание</h4>
      <h4>Маршрут</h4>
    </div>
    <div class="col-md-10">
      <div class="row col-md-10">
        <mrp title="Изменить"
             need_reload=true
             :route_url="router('account.travel.base.form', {'travel_id': travel_id})"
             btn_name=""
             class_arr="mr-btn-primary fa fa-pen">
        </mrp>
        <h4 class="ml-1">{{ travelDetails.name }}</h4>
      </div>
      <div class="row col-md-10">{{ travelDetails.description }}</div>
    </div>
  </div>
</template>

<script>
import mrp from './../../MrPopupForm.vue';

export default {
  components: {
    mrp
  },
  props: ['travel_id'],
  name: "page",

  data() {
    return {
      urlList: {
        'api.travel.details': '/api/travel/details',
        'account.travel.base.form': '/account/travel/{travel_id}/base/form',
      },
      travelDetails: {}
    }
  },
  created() {
    this.getTravelDetails();
  },

  methods: {
    router: function (route, params) {
      let url = this.urlList[route];
      for (let key in params) {
        url = url.replace('{' + key + '}', params[key]);
      }
      return url;
    },

    getTravelDetails: function () {
      axios.post(this.urlList['api.travel.details'], {'id': this.travel_id}).then(response => {
          if (response.data.result !== true) {
            console.log('Error');
            return;
          }

          this.travelDetails = response.data.content;
        }
      );
    },
  }
}
</script>

<style scoped>

</style>

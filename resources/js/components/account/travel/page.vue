<template>
    <div class="row col-md-12 col-sm-12 col-lg-12">
        <div class="col-md-2 col-sm-12 p-3 mr-left-side" style="border-radius: 5px;">
            <h4>Описание</h4>
            <h4>Участники</h4>
            <h4>Снаряжение</h4>
            <h4>Питание</h4>
            <h4>Маршрут</h4>
        </div>
        <div class="col-md-10">
            <div class="row col-md-10">
                <div class="row col-md-10">
                    <mrp title="Изменить"
                         @response="getTravelDetails"
                         :route_url="router('account.travel.base.form', {'travel_id': travel_id})"
                         class_arr="mr-btn-primary fa fa-pen">
                    </mrp>
                    <h4 class="ml-1">{{ travelDetails.name }}</h4>
                </div>
                <div class="row col-md-10">
                    <div>
                        <h5>{{ country.name }}</h5>
                        <h5>{{ travelType.name }}</h5>
                    </div>
                </div>
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
            travelDetails: {},
            country: {},
            travelType: {},
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
            axios.post(this.urlList['api.travel.details'], {'travel_id': this.travel_id}).then(response => {
                    if (response.data.result !== true) {
                        console.log('Error');
                        return;
                    }

                    this.travelDetails = response.data.content;
                    this.country = this.travelDetails.country;
                    this.travelType = this.travelDetails.travel_type;
                }
            );
        },
    }
}
</script>

<style scoped>

</style>

<template>
    <div class="card">
        <div class="card-header">{{ 'parking_lots_title' | translate }}</div>

        <div class="card-body">

            <table class="table">
                <thead>
                <tr>
                    <th>{{ 'name' | translate }}</th>
                    <th>{{ 'hourly_fare' | translate }}</th>
                    <th>{{ 'capacity' | translate }}</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr v-show="isLoading">
                    <td colspan="4" class="text-center">
                        {{ 'loading' | translate }}
                    </td>
                </tr>
                <tr v-for="lot in lots" v-show="!isLoading">
                    <td>
                        <router-link :to="{name: 'showLot', params: { id: lot.id }}">{{ lot.name }}</router-link>
                    </td>
                    <td>{{ lot.hourly_fare | formatNumber }}</td>
                    <td>{{ lot.taken_spots }}/{{ lot.capacity }}</td>
                    <td align="right">
                        <a :href="'/lots/'+lot.id" class="btn btn-light btn-sm">
                            <span class="oi oi-pencil"></span>
                        </a>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'lot-list',
        data: () => {
            return {
                isLoading: true
            }
        },
        created() {
            this.isLoading = true;
            window.axios.get(window.api.getLots)
                .then(({data}) => {
                    this.isLoading = false;
                    this.$store.state.lots = data.lots;
                });
        },
        computed: {
            lots() {
                return this.$store.state.lots;
            }
        }
    }
</script>
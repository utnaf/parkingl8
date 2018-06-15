<template>
    <div class="card">
        <div class="card-header">{{ pageTitle }}</div>

        <div class="card-body">

            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Hourly Fare</th>
                    <th>Capacity</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="lot in lots">
                    <td>
                        <router-link :to="{name: 'showLot', params: { id: lot.id }}">{{ lot.name }}</router-link>
                    </td>
                    <td>{{ lot.hourly_fare | formatNumber }}</td>
                    <td>{{ lot.taken_spots }}/{{ lot.capacity }}</td>
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
                'pageTitle': 'My ParkingLots'
            }
        },
        created() {
            window.axios.get(window.api.getLots)
                .then(({data}) => {
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
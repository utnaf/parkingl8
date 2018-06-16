<template>
    <div class="card">

        <div class="card-header">{{ 'entries_title' | translate }}</div>

        <div class="card-body">

            <message-alert></message-alert>

            <div class="row py-2">
                <div class="col-sm">
                    <router-link :to="{name: 'dashboard'}" class="btn btn-primary">
                        <span class="oi oi-chevron-left"></span>
                        {{ 'go_back' | translate }}
                    </router-link>

                    <form class="form-inline float-right">
                        <div class="form-check mb-2 mr-sm-2">
                            <input class="form-check-input" type="checkbox" id="showOnlyCarsInLot"
                                   v-model="showOnlyCarsInLot">
                            <label class="form-check-label" for="showOnlyCarsInLot">
                                {{ 'show_cars_in_lot' | translate }}
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>{{ 'id' | translate}}</th>
                    <th>{{ 'arrived_at' | translate}}</th>
                    <th>{{ 'has_payed_q' | translate}}</th>
                    <th>{{ 'has_gone_q' | translate}}</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="entry in entries" v-if="!showOnlyCarsInLot || showOnlyCarsInLot && entry.exited_at === null">
                    <td>{{ entry.id }}</td>
                    <td>{{ entry.arrived_at | formatDate('L') }} {{ entry.arrived_at | formatDate('LT') }}</td>
                    <td>{{ entry.payed_at ? entry.price : null | formatNumber }}</td>
                    <td><span class="oi oi-check" v-if="entry.exited_at !== null"></span></td>
                    <td align="right">
                        <entry-action :entry="entry"></entry-action>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import EntryAction from './EntryActionsComponent';
    import MessageAlert from './MessageAlertComponent';

    export default {
        name: 'entry-list',
        components: {
            EntryAction,
            MessageAlert
        },
        data: () => {
            return {
                showOnlyCarsInLot: true
            }
        },
        created() {
            window.axios.get(window.api.getEntries.replace(':id', this.$route.params.id))
                .then(({data}) => {
                    this.$store.state.entries = data.entries;
                });
        },
        computed: {
            entries() {
                return this.$store.state.entries;
            }
        }
    }
</script>
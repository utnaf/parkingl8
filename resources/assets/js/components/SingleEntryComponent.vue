<template>
    <div class="card">

        <div class="card-header">{{ 'entries_title' | translate }}</div>

        <div class="card-body">

            <message-alert></message-alert>

            <div v-show="isLoading" class="text-center py-4">
                {{ 'loading' | translate }}
            </div>

            <div v-if="!isLoading && entry !== null">
                <div class="row">
                    <div class="col-sm">
                        <h2 class="float-left">{{ 'details_for' | translate(entry.id)}}</h2>
                        <entry-action :entry="entry" class="float-right btn-lg"></entry-action>
                    </div>
                </div>
                
                <div class="row my-4">
                    <div class="col-sm">
                        <p class="p-1">
                            {{ 'arrived_at' | translate }}: <strong>{{ entry.arrived_at | formatDate }}</strong>
                        </p>
                        
                        <p class="p-1">
                            {{ 'parking_lot' | translate }}: <strong>{{ entry.parking_lot.name }}</strong>
                        </p>

                        <p class="p-1" v-show="entry.price">
                            {{ 'price' | translate }}: <strong>{{ entry.price | formatNumber }}</strong>
                        </p>

                        <p class="p-1" v-show="entry.payed_at">
                            {{ 'payed_at' | translate }}: <strong>{{ entry.payed_at | formatDate }}</strong>
                        </p>

                        <p class="p-1" v-show="entry.exited_at">
                            {{ 'exited_at' | translate }}: <strong>{{ entry.exited_at | formatDate }}</strong>
                        </p>
                    </div>
                </div>
                
                <p class="p1">
                    <router-link :to="{name: 'dashboard'}" class="btn btn-primary py-2">
                        <span class="oi oi-chevron-left"></span>
                        {{ 'dashboard' | translate }}
                    </router-link>
                </p>

            </div>
        </div>
    </div>
</template>

<script>
    import EntryAction from './EntryActionsComponent';
    import MessageAlert from './MessageAlertComponent';

    export default {
        name: 'single-entry',
        components: {
            EntryAction,
            MessageAlert
        },
        data: () => {
            return {
                isLoading: true,
                entry: null,
            }
        },
        created() {
            window.addEventListener('hashchange', this.searchEntry);
        },
        mounted() {
            this.searchEntry();
        },
        methods: {
            searchEntry() {
                window.axios.get(window.api.getEntry.replace(':id', this.$route.params.id))
                    .then(({data}) => {
                        this.entry = data.entry;
                        this.$store.state.entries.push(data.entry);
                        this.isLoading = false;
                    });
            }
        }
    }
</script>
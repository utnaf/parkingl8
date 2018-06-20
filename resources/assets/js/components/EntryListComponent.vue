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
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" v-model="listFilter" id="entryListFilterAll"
                                   :value="'all'">
                            <label class="form-check-label" for="entryListFilterAll">
                                {{ 'show_all' | translate }}
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" v-model="listFilter" id="entryListFilterPayed"
                                   :value="'payed'">
                            <label class="form-check-label" for="entryListFilterPayed">
                                {{ 'show_only_payed' | translate }}
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" v-model="listFilter" id="entryListFilterIn"
                                   :value="'inside'">
                            <label class="form-check-label" for="entryListFilterIn">
                                {{ 'show_only_inside' | translate }}
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row py-2">
                <div class="col-sm">
                    <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                {{ 'ticketId' | translate }}
                            </div>
                        </div>
                        <input type="search" 
                            class="form-control" 
                            :class="{'is-valid': highlightSearch}"
                            v-model="idToSearchFor"
                            :placeholder="'ticketPlaceholder' | translate">
                    </div>
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
                <tr v-show="isLoading">
                    <td colspan="5" class="text-center">
                        {{ 'loading' | translate }}
                    </td>
                </tr>
                <tr v-for="entry in entries" v-show="!isLoading">
                    <td>
                        <router-link :to="{name: 'singleEntry', params: {id: entry.id}}">
                            #{{ entry.id }}
                        </router-link>
                    </td>
                    <td>{{ entry.arrived_at | formatDate('L') }} {{ entry.arrived_at | formatDate('LT') }}</td>
                    <td>{{ entry.payed_at ? entry.price : null | formatNumber }}</td>
                    <td><span class="oi oi-check" v-if="entry.exited_at !== null"></span></td>
                    <td align="right">
                        <entry-action :entry="entry" class="btn-sm"></entry-action>
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
                listFilter: 'inside',
                isLoading: true,
                highlightSearch: false,
                idToSearchFor: '',
                previousListFilter: ''
            }
        },
        created() {
            this.isLoading = true;
            window.axios.get(window.api.getEntries.replace(':id', this.$route.params.id))
                .then(({data}) => {
                    this.isLoading = false;
                    this.$store.state.entries = data.entries;
                });
        },
        computed: {
            entries() {
                let entriesToShow = this.$store.state.entries;

                if(this.listFilter === 'payed') {
                    entriesToShow = this.$store.getters.payedNotExit
                }
                if(this.listFilter === 'inside') {
                    entriesToShow = this.$store.getters.inTheLot
                }

                if(this.idToSearchFor) {
                    entriesToShow = entriesToShow.filter((entry) => {
                        return entry.id == this.idToSearchFor;
                    });
                    this.highlightSearch = true;
                    if(this.previousListFilter === '') {
                        this.previousListFilter = this.listFilter;
                    }
                    this.listFilter = 'all';
                } 
                else {
                    this.listFilter = this.previousListFilter !== '' ? this.previousListFilter : this.listFilter;
                    this.highlightSearch = false;
                    this.previousListFilter = '';
                }

                return entriesToShow;
            }
        },
    }
</script>
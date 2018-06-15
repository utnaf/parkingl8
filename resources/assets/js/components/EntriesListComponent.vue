<template>
    <div class="card">
        <div class="card-header">{{ pageTitle }}</div>

        <div class="card-body">

            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Arrived at</th>
                    <th>Has payed?</th>
                    <th>Has gone?</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="entry in entries">
                    <td>{{ entry.id }}</td>
                    <td>{{ entry.arrived_at | formatDate('L') }} {{ entry.arrived_at | formatDate('LT') }}</td>
                    <td>{{ entry.payed_at == null ? 'Nope' : 'Yep' }}</td>
                    <td>{{ entry.exited_at === null ? 'Nope' : 'Yep' }}</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'entry-list',
        created() {
            window.axios.get(window.api.getEntries.replace(':id', this.$route.params.id))
                .then(({data}) => {
                    this.$store.state.entries = data.entries;
                });
        },
        computed: {
            entries() {
                return this.$store.state.entries;
            },
            pageTitle() {
                return 'Entries for ';
            }
        }
    }
</script>
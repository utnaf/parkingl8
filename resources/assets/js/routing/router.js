import VueRouter from 'vue-router';

require('vue').use(VueRouter);

// components
import lotList from '../components/LotListComponent.vue';
import entriesList from '../components/EntryListComponent.vue';
import singleEntry from '../components/SingleEntryComponent.vue';

const routes = [
    {
        path: '/',
        component: lotList,
        name: 'dashboard'
    },
    {
        path: '/lots/:id',
        component: entriesList,
        name: 'showLot'
    },
    {
        path: '/entry/:id',
        component: singleEntry,
        name: 'singleEntry'
    }
];

export default new VueRouter({
    routes: routes
});
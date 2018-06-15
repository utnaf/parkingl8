import VueRouter from 'vue-router';

require('vue').use(VueRouter);

// components
import lotList from '../components/LotListComponent.vue';
import entriesList from '../components/EntriesListComponent.vue';

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
    }
];

export default new VueRouter({
    routes: routes
});
const Vuex = require('vuex');
require('vue').use(Vuex);

export default new Vuex.Store({
    state: {
        lots: [

        ],
        entries: [

        ]
    },
    mutations: {
        updateEntryPrice(state, index, price) {
            if(state.entries[index]) {
                state.entries[index].price = price;
            }
        }
    }
});


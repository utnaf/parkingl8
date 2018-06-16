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
        updateEntryPrice(state, payload) {
            if(state.entries[payload.index]) {
                state.entries[payload.index].price = payload.price;
            }
        },
        updateEntry(state, payload) {
            if(state.entries[payload.index]) {
                state.entries[payload.index].payed_at = payload.entry.payed_at;
                state.entries[payload.index].exited_at = payload.entry.exited_at;
                state.entries[payload.index].price = payload.entry.price;
            }
        }
    }
});


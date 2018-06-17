const Vuex = require('vuex');
require('vue').use(Vuex);

export default new Vuex.Store({
    state: {
        lots: [

        ],
        entries: [

        ],
        user: window.config.user
    },
    getters: {
        // this is bad I know sorry sorry
        lot: (state) => (id) => {
            return state.lots.filter(lot => lot.id === id)
        },
        inTheLot: (state) => {
            return state.entries.filter(entry => entry.exited_at === null)
        },
        payedNotExit: (state) => {
            return state.entries.filter(entry => entry.payed_at !== null && entry.exited_at === null)
        }
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


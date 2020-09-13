import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        inCart: [],
        inWhishlist: [],
        inSavedlist: [],
        total: 0,
        subtotal: 0,
    },
    getters: {
        // TODO: Add getters
        inCart: state => state.inCart,
        inWhishlist: state => state.inWhishlist,
        inSavedlist: state => state.inSavedlist,
        total: state => state.total,
        subtotal: state => state.subtotal,
    },
    mutations: {
        // TODO: Add mutations
        ADD_TO_CART(state, certificate_id) { state.inCart.push(certificate_id); },
        REMOVE_FROM_CART(state, certificate_id) {
            let filteredItems = state.inCart.filter(item => item !== certificate_id)
            state.inCart = filteredItems;
        },

        ADD_TO_WHISHLIST(state, certificate_id) { state.inWhishlist.push(certificate_id); },
        REMOVE_FROM_WHISHLIST(state, certificate_id) {
            let filteredItems = state.inWhishlist.filter(item => item !== certificate_id)
            state.inWhishlist = filteredItems;
        },

        ADD_TO_SAVEDLIST(state, certificate_id) { state.inSavedlist.push(certificate_id); },
        REMOVE_FROM_SAVEDLIST(state, certificate_id) {
            let filteredItems = state.inSavedlist.filter(item => item !== certificate_id)
            state.inSavedlist = filteredItems;
        },
        ADD_TOTAL(state, total) { state.total = total; },
        ADD_SUBTOTAL(state, subtotal) { state.subtotal = subtotal; },
        RESET_STATE(state) {
            state.inCart = [];
            state.total = 0;
            state.subtotal = 0;
        },

        initialiseStore(state) {
            // Check if the ID exists
            // if (localStorage.getItem('store')) {
            //     // Replace the state object with the stored item
            //     this.replaceState(
            //         Object.assign(state, JSON.parse(localStorage.getItem('store')))
            //     );
            // }
        }
    },
    actions: {
        // TODO: Add actions
        addToCart(context, certificate_id) {
            context.commit('ADD_TO_CART', certificate_id);
            context.commit('REMOVE_FROM_WHISHLIST', certificate_id);
            context.commit('REMOVE_FROM_SAVEDLIST', certificate_id);
        },
        removeFromCart(context, certificate_id) {
            context.commit('REMOVE_FROM_CART', certificate_id);
        },
        addToWhishlist(context, certificate_id) {
            context.commit('ADD_TO_WHISHLIST', certificate_id);
            context.commit('REMOVE_FROM_CART', certificate_id);
        },
        removeFromWhishlist(context, certificate_id) {
            context.commit('REMOVE_FROM_WHISHLIST', certificate_id);
        },
        addToSavedlist(context, certificate_id) {
            context.commit('ADD_TO_SAVEDLIST', certificate_id);
            context.commit('REMOVE_FROM_CART', certificate_id);
        },
        removeFromSavedlist(context, certificate_id) {
            context.commit('REMOVE_FROM_SAVEDLIST', certificate_id);
        },
        addTotal(context, total) {
            context.commit('ADD_TOTAL', total);
        },
        addSubTotal(context, subtotal) {
            context.commit('ADD_SUBTOTAL', subtotal);
        },
        resetState(context) {
            context.commit('RESET_STATE');
        },
    },
});
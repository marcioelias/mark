import VuexPersist from 'vuex-persist'

const vuexLS = new VuexPersist({
    key: process.env.MIX_APP_NAME,
    storage: window.localStorage,
    reducer: state => ({
        funnel: state.funnel
    })
})

export default [
    vuexLS.plugin
]

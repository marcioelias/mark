import * as types from './mutation-types'

export default {
    [types.SET_FUNNEL] (state, payload) {
        state.funnel = payload
    },
    [types.SET_STEPS] (state, payload) {
        state.steps = payload
    },
    [types.SET_LEADS] (state, payload) {
        state.leads = payload
    },
    [types.SET_CURRENT_SCHEDULE] (state, payload) {
        state.currentSchedule = payload
    }
}

import * as types from './mutation-types'
import Vue from 'vue'

export default {
    [types.SET_ID] (state, payload) {
        state.id = payload
    },
    [types.SET_ACTIONS] (state, payload) {
        state.actions = payload
    },
    [types.SET_FUNNEL_STEP_SEQUENCE] (state, payload) {
        state.funnel_step_sequence = payload
    },
    [types.SET_ACTION_COMPONENT] (state, payload) {
        state.actionComponent = payload
    },
    [types.ADD_NEW_ACTION] (state, payload) {
        /*
         * save the current state on a local variable an re-inicialize the state altered in order to get
         * propper reactivity
         */
        let tmp = state.actions
        tmp.push(payload)
        state.actions = null
        state.actions = tmp
    },
    [types.DELETE_ACTION] (state, payload) {
        state.actions.splice(payload, 1)
    },
    [types.SET_ACTION_EDITING_INDEX] (state, payload) {
        state.actionEditingIndex = payload
    },
    [types.UPDATE_ACTION] (state, payload) {
        /*
         * save the current state on a local variable an re-inicialize the state altered in order to get
         * propper reactivity
         */
        const tmp = state.actions.find(a => a.action_sequence === payload.action_sequence)
        Object.assign(tmp, payload)
        state.actionEditingIndex = null
    },
    [types.LOAD_STATE] (state, payload) {
        Object.assign(state, { ...payload })
    },
    [types.SET_ACTION_DELETED] (state, payload) {
        state.actions[payload].deleted = true
    },
    /* [types.UPDATE_ACTION_SEQUENCE] (state) {
        const dayToSeconds = value => value * 86400
        const minutesToSeconds = value => value * 60
        const getDelay = act => dayToSeconds(act.action_data.options.days_after || 0) + minutesToSeconds(act.action_data.options.delay_minutes || 0)

        let aux = state.actions.filter(a => !a.deleted)
                               .sort((a, b) => getDelay(a) - getDelay(b) || a.action_sequence - b.action_sequence)

        console.log()
        Object.assign(state.actions, aux.map((act, idx) => act.action_sequence = idx))

        console.log('ajustando sequencias')
        console.log(state.actions)
    } */
}

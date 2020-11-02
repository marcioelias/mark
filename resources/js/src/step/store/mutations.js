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
        let tmp = state.actions
        state.actions = null
        tmp[state.actionEditingIndex] = payload
        state.actions = tmp
        state.actionEditingIndex = null
    },
    [types.LOAD_STATE] (state, payload) {
        Object.assign(state, { ...payload })
    },
    [types.SET_ACTION_DELETED] (state, payload) {
        state.actions[payload].deleted = true
    }
}

import * as types from './mutation-types'

export default {
    [types.SET_ID] (state, payload) {
        state.id = payload
    },
    [types.SET_FUNNEL_STEP_SEQUENCE] (state, payload) {
        state.funnel_step_sequence = payload
    },
    [types.SET_FUNNEL_STEP_DESCRIPTION] (state, payload) {
        state.funnel_step_description = payload
    },
    [types.SET_NEW_TAG_ID] (state, payload) {
        state.new_tag_id = payload
    },
    [types.SET_DELAY_DAYS] (state, payload) {
        state.delay_days = payload
    },
    [types.SET_DELAY_HOURS] (state, payload) {
        state.delay_hours = payload
    },
    [types.SET_ACTIONS] (state, payload) {
        state.actions = payload
    },
    [types.SET_ACTION_COMPONENT] (state, payload) {
        state.actionComponent = payload
    },
    [types.ADD_NEW_ACTION] (state, payload) {
        state.actions.push(payload)
    },
    [types.DELETE_ACTION] (state, payload) {
        state.actions.splice(payload, 1)
    },
    [types.SET_ACTION_EDITING_INDEX] (state, payload) {
        state.actionEditingIndex = payload
    },
    [types.UPDATE_ACTION] (state, payload) {
        state.actions[state.actionEditingIndex] = payload
        state.actionEditingIndex = null
    },
    [types.LOAD_STATE] (state, payload) {
        Object.assign(state, { ...payload })
    },
    [types.SET_ACTION_DELETED] (state, payload) {
        state.actions[payload].deleted = true
    }
}

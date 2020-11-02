import * as types from './mutation-types'

export default {
    [types.SET_ACTION_ID] (state, payload) {
        state.id = payload
    },
    [types.SET_ACTION_TYPE_ID] (state, payload) {
        state.action_type_id = payload
    },
    [types.SET_ACTION_SEQUENCE] (state, payload) {
        state.action_sequence = payload
    },
    [types.SET_ACTION_DESCRIPTION] (state, payload) {
        state.action_description = payload
    },
    [types.ACTION_DATA] (state, payload) {
        state.action_data = payload
    },
    [types.LOAD_STATE] (state, payload) {
        Object.assign(state, payload)
    }
}

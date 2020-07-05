import * as types from './mutation-types'

export default {
    [types.SET_DESCRIPTION] (state, payload) {
        state.description = payload
    },
    [types.SET_ACTION_DATA] (state, payload) {
        state.actionData = payload
    }
}

import * as types from './mutation-types'

export default {
    [types.SET_DESCRIPTION] (state, payload) {
        state.description = payload
    },
    [types.SET_TEXT_MESSAGE] (state, payload) {
        state.textMessage = payload
    }
}

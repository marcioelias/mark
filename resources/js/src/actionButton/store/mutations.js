import * as types from './mutation-types'

export default {
    [types.SET_ACTION_TYPES] (state, payload) {
        state.actionTypes = payload
    },
    [types.SET_IS_LOADING] (state, payload) {
        state.isLoading = payload
    }
}

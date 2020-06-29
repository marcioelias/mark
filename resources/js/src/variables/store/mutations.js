import * as types from './mutation-types'

export default {
    [types.SET_VARIABLES] (state, payload) {
        state.variables = payload
    }
}

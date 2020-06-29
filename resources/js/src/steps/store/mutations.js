import * as types from './mutation-types'

export default {
    [types.SET_ACTIVE_COMPONENT] (state, payload) {
        state.activeComponent = payload
    },
    [types.SET_LIST_ACTIONS] (state, payload) {
        state.listActions = payload
    },
    [types.SET_NEW_ACTION] (state, payload) {
        state.listActions.push(payload)
    },
    [types.DEL_ACTION] (state, payload) {
        state.listActions.splice(payload, 1)
    },
    [types.EDITING_INDEX] (state, payload) {
        state.editingIndex = payload
    },
    [types.IS_EDITING] (state, payload) {
        state.isEditing = payload
    },
    [types.UPDATE_ACTION] (state, payload) {
        state.listActions[payload.index] = payload.data
    }
}

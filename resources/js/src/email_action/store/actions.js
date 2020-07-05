import * as types from './mutation-types'

export const ActionSetDescription = ({ commit }, payload) => {
    commit(types.SET_DESCRIPTION, payload)
}

export const ActionSetActionData = ({ commit }, payload) => {
    commit(types.SET_ACTION_DATA, payload)
}

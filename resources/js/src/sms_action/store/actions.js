import * as types from './mutation-types'

export const ActionSetDescription = ({ commit }, payload) => {
    commit(types.SET_DESCRIPTION, payload)
}

export const ActionSetTextMessage = ({ commit }, payload) => {
    commit(types.SET_TEXT_MESSAGE, payload)
}

import * as types from './mutation-types'

export const ActionSetDescription = ({ commit }, payload) => {
    commit(types.SET_DESCRIPTION, payload)
}

export const ActionSetEmailMessage = ({ commit }, payload) => {
    commit(types.SET_EMAIL_MESSAGE, payload)
}

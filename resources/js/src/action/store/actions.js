import * as types from './mutation-types'
import { setupActionState } from './state'

export const ActionSetId = ({ commit }, payload) => {
    commit(types.SET_ACTION_ID, payload)
}

export const ActionSetActionTypeId = ({ commit }, payload) => {
    commit(types.SET_ACTION_TYPE_ID, payload)
}

export const ActionSetActionSequence = ({ commit }, payload) => {
    commit(types.SET_ACTION_SEQUENCE, payload)
}

export const ActionSetActionDescription = ({ commit }, payload) => {
    commit(types.SET_ACTION_DESCRIPTION, payload)
}

export const ActionSetActionData = ({ commit }, payload) => {
    commit(types.ACTION_DATA, payload)
}

export const ActionClearState = ({ commit }) => {
    commit(types.LOAD_STATE, setupActionState())
}

export const ActionEditAction = ({ commit }, payload) => {
    return new Promise((resolve, _) => {
        commit(types.LOAD_STATE, payload)
        resolve()
    })
}

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
    return new Promise((resolve, _) => {
        commit(types.ACTION_DATA, payload)
        resolve()
    })
}

export const ActionSetRemarketingFunnels = async ({ commit }, { vm }) => {
    await vm.$http.get('/funnels/remarketing/json')
                    .then(res => commit(types.SET_REMARKETING_FUNNELS, res.data))
                    .catch(err => console.log(err))
}

export const ActionClearState = ({ commit }) => {
    return new Promise((resolve, _) => {
        commit(types.LOAD_STATE, setupActionState())
        resolve()
    })
}

export const ActionEditAction = ({ commit }, payload) => {
    return new Promise((resolve, _) => {
        commit(types.LOAD_STATE, payload)
        resolve()
    })
}

export const ActionResetState = ({ commit }) => {
    return new Promise((resolve, _) => {
        commit(types.RESET_STATE, payload)
        resolve()
    })
}

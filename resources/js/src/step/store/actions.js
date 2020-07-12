import * as types from './mutation-types'
import { setupState } from './state'

export const ActionSetId = ({ commit }, payload) => {
    commit(types.SET_ID, payload)
}

export const ActionSetFunnelStepSequence = ({ commit }, payload) => {
    commit(types.SET_FUNNEL_STEP_SEQUENCE, payload)
}

export const ActionSetFunnelStepDescription = ({ commit }, payload) => {
    commit(types.SET_FUNNEL_STEP_DESCRIPTION, payload)
}

export const ActionSetNewTagId = ({ commit }, payload) => {
    commit(types.SET_NEW_TAG_ID, payload)
}

export const ActionSetDelayDays = ({ commit }, payload) => {
    commit(types.SET_DELAY_DAYS, payload)
}

export const ActionSetDelayHours = ({ commit }, payload) => {
    commit(types.SET_DELAY_HOURS, payload)
}

export const ActionSetActions = ({ commit }, payload) => {
    commit(types.SET_ACTIONS, payload)
}

export const ActionSetActionComponent = ({ commit }, payload) => {
    commit(types.SET_ACTION_COMPONENT, payload)
}

export const ActionAddNewAction = ({ commit, rootState }) => {
    return new Promise((resolve, reject) => {
        commit(types.ADD_NEW_ACTION, { ...rootState.action })
        resolve()
    })
}

export const ActionUpdateAction = ({ commit, rootState }) => {
    return new Promise((resolve, reject) => {
        commit(types.UPDATE_ACTION, { ...rootState.action })
        resolve()
    })
}

export const ActionSetEditActionIndex = ({ commit }, payload) => {
    commit(types.SET_ACTION_EDITING_INDEX, payload)
}

export const ActionDelAction = ({ commit, state }, payload) => {
    let act = state.actions[payload]
    if (act.id) {
        commit(types.SET_ACTION_DELETED, payload)
    } else {
        commit(types.DELETE_ACTION, payload)
    }
}

export const ActionLoadStep = ({ commit, rootState}) => {
    return new Promise((resolve, _) => {
        commit(types.LOAD_STATE, rootState.funnel.steps[rootState.funnel.currentStep])
        resolve()
    })
}

export const ActionClearState = ({ commit }) => {
    commit(types.LOAD_STATE, setupState())
}

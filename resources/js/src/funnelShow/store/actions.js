import * as types from './mutation-types'

export const ActionSetFunnel = ({ commit }, payload) => {
    commit(types.SET_FUNNEL, payload)
}

export const ActionSetSteps = ({ commit }, payload) => {
    commit(types.SET_STEPS, payload)
}

export const ActionSetLeads = ({ commit }, payload) => {
    commit(types.SET_LEADS, payload)
}

export const ActionLoadFunnel = ({ dispatch }, { vm, id }) => {
    return new Promise((resolve, _) => {
        vm.$http.get(`funnel/${id}/json`)
            .then(res => {
                dispatch('ActionSetFunnel', res.data)
                dispatch('ActionSetSteps', res.data.steps)
            })
        resolve()
    })
}

export const ActionSetCurrentSchedule = ({ commit }, payload) => {
    commit(types.SET_CURRENT_SCHEDULE, payload)
}

import * as types from './mutation-types'

export const ActionSetDescription = ({ commit }, payload) => {
    commit(types.SET_DESCRIPTION, payload)
}
export const ActionSetSteps = ({ commit }, payload) => {
    commit(types.SET_STEPS, payload)
}

export const ActionAddNewStep = ({ state, commit }, payload) => {
    return new Promise((resolve, _) => {
        payload.funnel_step_sequence = (state.steps.length ?? 0) + 1
        commit(types.ADD_NEW_STEP, payload)
        resolve()
    })
}

export const ActionUpdateStep = ({ commit }, payload) => {
    return new Promise((resolve, _) => {
        commit(types.UPDATE_STEP, payload)
        resolve()
    })
}

export const ActionSetIsSalesFunnel = ({ commit }, payload) => {
    commit(types.SET_IS_SALES_FUNNEL, payload)
}

export const ActionSetActive = ({ commit }, payload) => {
    commit(types.SET_ACTIVE, payload)
}

export const ActionSetShowCrudStep = ({ commit }, payload) => {
    commit(types.SET_SHOW_CRUD_STEPS, payload)
}

export const ActionSetShowCrudAction = ({ commit }, payload) => {
    commit(types.SET_SHOW_CRUD_ACTION, payload)
}

export const ActionClearState = ({ commit }) => {
    commit(types.SET_STEPS, [])
}

export const ActionGetActionTypes = async ({ commit }, { vm }) => {
    await vm.$http.get('action_types/json')
                  .then(res => commit(types.SET_ACTION_TYPES, res.data))
                  .catch(err => console.log(err))
}

export const ActionGetPostbackEventTypes = async ({ commit }, { vm }) => {
    await vm.$http.get('postback_event_types/json')
                  .then(res => commit(types.SET_POSTBACK_EVENT_TYPES, res.data))
}

export const ActionSaveFunnel = ({ state }, { vm }) => {
    if (state.isEditing) {
        return vm.$http.put(`funnel/${state.funnelId}`, {
            id: state.funnelId,
            description: state.description,
            is_sales_funnel: state.isSalesFunnel,
            active: state.active,
            steps: {
                ...state.steps
            }
        })
    } else {
        return vm.$http.post('funnel', {
            description: state.description,
            is_sales_funnel: state.isSalesFunnel,
            active: state.active,
            steps: {
                ...state.steps
            }
        })
    }

}

export const ActionSetCurrentStep = ({ commit }, payload) => {
    commit(types.SET_CURRENT_STEP, payload)
}

export const ActionLoadFunnel = async ({ commit, dispatch }, { vm, id }) => {
    await vm.$http.get(`funnel/${id}/json`)
                .then(res => {
                    dispatch('ActionClearState')
                    commit(types.SET_FUNNEL_IS_EDITING, true)
                    commit(types.SET_FUNNEL_ID, res.data.id)
                    commit(types.SET_DESCRIPTION, res.data.funnel_description)
                    commit(types.SET_IS_SALES_FUNNEL, res.data.is_sales_funnel)
                    commit(types.SET_ACTIVE, res.data.active)
                    commit(types.SET_STEPS, res.data.steps)
                    commit(types.SET_IS_LOADING, false)
            })
}

export const ActionIsEditingStep = ({ commit }, payload) => {
    commit(types.SET_IS_EDITING_STEP, payload)
}

export const ActionSetHttpErrors = ({ commit }, payload) => {
    commit(types.SET_HTTP_ERRORS, payload)
}

export const ActionSetIsLoading = ({ commit }, payload) => {
    commit(types.SET_IS_LOADING, payload)
}

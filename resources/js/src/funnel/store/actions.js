import * as types from './mutation-types'

export const ActionSetProduct = ({ commit }, payload) => {
    commit(types.SET_PRODUCT, payload)
}
export const ActionSetSteps = ({ commit }, payload) => {
    commit(types.SET_STEPS, payload)
}

export const ActionAddNewStep = ({ state, commit }, payload) => {
    return new Promise((resolve, _) => {
        payload.funnel_step_sequence = (state.steps.length ?? 0) + 1
        payload.funnel_step_description = `Passo ${payload.funnel_step_sequence}`
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

export const ActionGetProducts = async ({ commit }, { vm }) => {
    await vm.$http.get('products/json')
            .then(res => commit(types.SET_PRODUCTS, res.data))
            .catch(err => console.log(err))
}

export const ActionGetTags = async ({ commit }, { vm }) => {
    await vm.$http.get(`tags/json`)
                  .then(res => commit(types.SET_TAGS, res.data))
                  .catch(err => console.log(err))
}

export const ActionSetTag = ({ commit }, payload) => {
    commit(types.SET_TAG, payload)
}

export const ActionSetActive = ({ commit }, payload) => {
    commit(types.SET_ACTIVE, payload)
}

export const ActionSetShowCrudStep = ({ commit }, payload) => {
    commit(types.SET_SHOW_CRUD_STEPS, payload)
}

export const ActionClearState = ({ commit }) => {
    commit(types.SET_STEPS, [])
    commit(types.SET_TAG, null)
    commit(types.SET_PRODUCT, null)
}

export const ActionGetActionTypes = async ({ commit }, { vm }) => {
    await vm.$http.get('action_types/json')
                  .then(res => commit(types.SET_ACTION_TYPES, res.data))
                  .catch(err => console.log(err))
}

export const ActionSaveFunnel = ({ state }, { vm }) => {
    if (state.isEditing) {
        return vm.$http.put(`funnel/${state.funnelId}`, {
            id: state.funnelId,
            product_id: state.product,
            tag_id: state.tag,
            active: state.active,
            steps: {
                ...state.steps
            }
        })
    } else {
        return vm.$http.post('funnel', {
            product_id: state.product,
            tag_id: state.tag,
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

export const ActionLoadFunnel = ({ commit, dispatch }, { vm, id }) => {
    return new Promise((resolve, _) => {
        vm.$http.get(`funnel/${id}/json`)
            .then(res => {
                dispatch('ActionClearState')
                commit(types.SET_FUNNEL_IS_EDITING, true)
                commit(types.SET_FUNNEL_ID, res.data.id)
                commit(types.SET_PRODUCT, res.data.product_id)
                commit(types.SET_TAG, res.data.tag_id)
                commit(types.SET_ACTIVE, res.data.active)
                commit(types.SET_STEPS, res.data.steps)
                commit(types.SET_IS_LOADING, false)
            })
        resolve()
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

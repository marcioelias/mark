import * as types from './mutation-types'

export const ActionSetProduct = ({ commit }, payload) => {
    commit(types.SET_PRODUCT, payload)

}
export const ActionSetSteps = ({ commit }, payload) => {
    commit(types.SET_STEPS, payload)
}

export const ActionAddNewStep = ({ state, commit }, payload) => {
    payload.funnel_step_sequence = (state.steps.length ?? 0) + 1
    payload.funnel_step_description = `Passo ${payload.funnel_step_sequence}`
    commit(types.ADD_NEW_STEP, payload)
}

export const ActionUpdateStep = ({ commit }, payload) => {
    commit(types.UPDATE_STEP, payload)
}

export const ActionGetProducts = async ({ commit }, { vm }) => {
    await vm.$http.get('products/json')
            .then(res => commit(types.SET_PRODUCTS, res.data))
            .catch(err => console.log(err))
}

export const ActionGetTags = async ({ commit }, { vm }) => {
    await vm.$http.get('tags/json')
                  .then(res => commit(types.SET_TAGS, res.data))
                  .catch(err => console.log(err))
}

export const ActionSetTag = ({commit}, payload) => {
    commit(types.SET_TAG, payload)
}

export const ActionSetActive = ({ commit }, payload) => {
    commit(types.SET_ACTIVE, payload)
}

export const ActionSetShowCrudStep = ({ commit }, payload) => {
    commit(types.SET_SHOW_CRUD_STEPS, payload)
}

export const ActionClearState = ({ commit }) => {
    commit(types.SET_PRODUCT, {})
    commit(types.SET_STEPS, [])
}

export const ActionGetActionTypes = async ({ commit }, { vm }) => {
    await vm.$http.get('action_types/json')
                  .then(res => commit(types.SET_ACTION_TYPES, res.data))
                  .catch(err => console.log(err))
}

export const ActionSaveFunnel = async ({ state, dispatch }, { vm }) => {
    await vm.$http.post('funnel', {
                product_id: state.product,
                tag_id: state.tag,
                active: state.active,
                steps: {
                    ...state.steps
                }
            })
            .then(res => res.status && console.log(res.data)) //dispatch('ActionClearState'))

}

export const ActionLoadFunnel = async ({ commit, dispatch }, { vm, id }) => {
    await vm.$http.get(`funnel/${id}/json`)
            .then(res => {
                console.log(res.data)
                dispatch('ActionClearState')
                commit(types.SET_PRODUCT, res.data.product_id)
                commit(types.SET_TAG, res.data.tag_id)
                commit(types.SET_ACTIVE, res.data.active)
                commit(types.SET_STEPS, res.data.steps)
            })
}

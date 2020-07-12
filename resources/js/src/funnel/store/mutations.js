import * as types from './mutation-types'

export default {
    [types.SET_PRODUCT] (state, payload) {
        state.product = payload
    },
    [types.SET_STEPS] (state, payload) {
        state.steps = payload
    },
    [types.ADD_NEW_STEP] (state, payload) {
        state.steps.push(payload)
    },
    [types.UPDATE_STEP] (state, payload) {
        state.steps[payload.index] = payload.data
    },
    [types.SET_PRODUCTS] (state, payload) {
        state.products = payload
    },
    [types.SET_TAGS] (state, payload) {
        state.tags = payload
    },
    [types.SET_TAG] (state, payload) {
        state.tag = payload
    },
    [types.SET_ACTIVE] (state, payload) {
        state.active = payload
    },
    [types.SET_SHOW_CRUD_STEPS] (state, payload) {
        state.showCrudStep = payload
    },
    [types.SET_ACTION_TYPES] (state, payload) {
        state.actionTypes = payload
    },
    [types.SET_CURRENT_STEP] (state, payload) {
        state.currentStep = payload
    },
    [types.SET_IS_EDITING_STEP] (state, payload) {
        state.isEditingStep = payload
    },
    [types.SET_HTTP_ERRORS] (state, payload) {
        state.httpErrors = payload
    }
}

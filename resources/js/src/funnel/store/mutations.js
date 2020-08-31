import * as types from './mutation-types'

export default {
    [types.SET_DESCRIPTION] (state, payload) {
        state.description = payload
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
    [types.SET_IS_SALES_FUNNEL] (state, payload) {
        state.isSalesFunnel = payload
    },
    [types.SET_ACTIVE] (state, payload) {
        state.active = payload
    },
    [types.SET_SHOW_CRUD_STEPS] (state, payload) {
        state.showCrudStep = payload
    },
    [types.SET_SHOW_CRUD_ACTION] (state, payload) {
        state.showCrudAction = payload
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
    },
    [types.SET_IS_LOADING] (state, payload) {
        state.isLoading = payload
    },
    [types.SET_FUNNEL_IS_EDITING] (state, payload) {
        state.isEditing = payload
    },
    [types.SET_FUNNEL_ID] (state, payload) {
        state.funnelId = payload
    },
    [types.SET_POSTBACK_EVENT_TYPES] (state, payload) {
        state.postbackEventTypes = payload
    }
}

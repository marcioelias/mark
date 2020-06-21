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
    [types.SET_ORIGINAL_TAG] (state, payload) {
        state.orignalTag = payload
    },
    [types.SET_NEW_TAG] (state, payload) {
        state.newTag = payload
    },
    [types.SET_ACTIVE] (state, payload) {
        state.active = payload
    }
}

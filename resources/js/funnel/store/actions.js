import * as types from './mutation-types'

export const ActionSetProduct = ({ commit }, payload) => {
    commit(types.SET_PRODUCT, payload)

}
export const ActionSetSteps = ({ commit }, payload) => {
    commit(types.SET_STEPS, payload)
}

export const ActionAddNewStep = ({ commit }, payload) => {
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

export const ActionSetOriginalTag = ({commit}, payload) => {
    commit(types.SET_ORIGINAL_TAG, payload)
}

export const ActionSetNewTag = ({ commit }, payload) => {
    commit(types.SET_NEW_TAG, payload)
}

export const ActionSetActive = ({ commit }, payload) => {
    commit(types.SET_ACTIVE, payload)
}

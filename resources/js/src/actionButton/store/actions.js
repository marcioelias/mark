import * as types from './mutation-types'
export const ActionSetActionTypes = async ({ commit }, { vm }) => {
    commit(types.SET_IS_LOADING, true)
    await vm.$http.get('action_types/json')
                .then(res => {
                    commit(types.SET_ACTION_TYPES, res.data)
                    commit(types.SET_IS_LOADING, false)
                })
}

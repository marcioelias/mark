import * as types from './mutation-types'

export const ActionSetVariables = ({ commit }, payload) => {
    commit(types.SET_VARIABLES, payload)
}

export const ActionGetVariablesFromApi = async ({ commit }, { vm }) => {
    await vm.$http.get('/variables/json')
            .then(res => commit(types.SET_VARIABLES, res.data))
            .catch(err => console.log(err))
}

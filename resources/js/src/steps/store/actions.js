import * as types from './mutation-types'
import * as componentTypes from '../components/component-types'

export const ActionSetActiveComponent = ({ commit }, payload) => {
    commit(types.SET_ACTIVE_COMPONENT, payload)
}

export const ActionSetListActions = ({ commit }, payload) => {
    commit(types.SET_LIST_ACTIONS, payload)
}

export const ActionSetNewAction = ({ commit }, payload) => {
    commit(types.SET_NEW_ACTION, payload)
}

export const ActionDelAction = ({ commit }, payload) => {
    commit(types.DEL_ACTION, payload)
}

export const ActionEditAction = ({ state, commit }, payload) => {
    commit(types.EDITING_INDEX, payload)
    commit(types.IS_EDITING, true)
    switch (state.listActions[payload].type) {
        case 'sms':
            commit(types.SET_ACTIVE_COMPONENT, componentTypes.COMPONENT_NEW_SMS)
            break;

        default:
            commit(types.SET_ACTIVE_COMPONENT, componentTypes.COMPONENT_TABLE)
            break;
    }
}

export const ActionSetUpdateAction = ({ state, commit }, payload) => {
    commit(types.UPDATE_ACTION, { index: state.editingIndex, data: payload })
    commit(types.IS_EDITING, false)
    commit(types.EDITING_INDEX, null)
}

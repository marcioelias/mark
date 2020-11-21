import * as types from './mutation-types'

export const ActionSetHttpErrors = ({ commit }, payload) => {
    commit(types.SET_HTTP_ERRORS, payload)
}

export const ActionSetIsLoading = ({ commit }, payload) => {
    commit(types.SET_IS_LOADING, payload)
}

export const ActionSetCustomers = ({ commit }, payload) => {
    commit(types.SET_CUSTOMERS, payload)
}

export const ActionSetSelectedState = ({ commit }, payload) => {
    commit(types.SET_STATUS_SELECTED, payload)
}

export const ActionAddSelectedColumn = ({ commit }, payload) => {
    commit(types.SET_SELECTED_COLUMNS, payload)
}

export const ActionSetSeparator = ({ commit}, payload) => {
    commit(types.SET_SEPARATOR, payload)
}

export const ActionSetImportFile = ({ commit }, payload) => {
    commit(types.SET_IMPORT_FILE, payload)
}

export const ActionSetFirstLineCaption = ({ commit }, payload) => {
    commit(types.SET_FIRST_LINE_CAPTION, payload)
}

export const ActionSetColumnOfData = ({ commit }, payload) => {
    commit(types.SET_COLUMN_OF_DATA, payload)
}

export const ActionSetOrderField = ({ commit }, payload) => {
    commit(types.SET_ORDER_FIELD, payload)
}

export const ActionSetOrderType = ({ commit }, payload) => {
    commit(types.SET_ORDER_TYPE, payload)
}

export const ActionSetStatuses = ({ commit }, { vm }) => {
    vm.$http.get('customer/statuses/json')
            .then(res => commit(types.SET_STATUSES, res.data))
            .catch(err => console.log(err))
}

export const ActionFileUpload = async ({ state, commit }, { vm }) => {
    let formData = new FormData()
    formData.append('file', state.importFile)
    formData.append('separator', state.separator)
    commit(types.SET_IS_LOADING, true)

    await vm.$http.post('/customers/upload',
        formData,
        {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }
    ).then(res => {
        commit(types.SET_CUSTOMERS, res.data)
        commit(types.SET_IS_LOADING, false)
    })
}

export const ActionSaveImportedData = async ({ state, getters }, { vm }) => {
    return vm.$http.post('customers/import', {
        customer_status_id: state.selectedState,
        customers: getters.GetCustomersForImport
    })
}

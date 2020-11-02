export const GetVariables = state => {
    return state.variables
}

export const GetVariablesAsObject = state => {
    let res = {}
    state.variables.forEach(e => {
        res[e.description] = e.variable
    });

    return res
}

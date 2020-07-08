export const GetActionByIndex = state => index => {
    return state.listActions[index]
}

export const OrderedListActions = state => {
    return state.listActions.sort((a, b) => a.action_sequence > b.action_sequence)
}

export const OrderedActions = state => {
    let acts = state.actions.filter(a => !a.deleted)
    return acts.sort((a, b) => a.action_sequence - b.action_sequence)
}

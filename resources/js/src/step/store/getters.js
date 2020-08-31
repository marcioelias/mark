export const OrderedActions = state => {
    let acts = state.actions.filter(a => !a.deleted)
    let aux = acts.sort((a, b) => {
        if (a.action_data.options.days_after > b.action_data.options.days_after) return 1
        if (a.action_data.options.days_after < b.action_data.options.days_after) return -1
        if (a.action_sequence > b.action_sequence) return 1
        if (a.action_sequence < b.action_sequence) return -1
        //a.action_sequence - b.action_sequence && a.action_data.options.days_after - b.action_data.options.days_after
    })
    console.log(aux)
    return aux
}

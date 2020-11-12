export const OrderedActions = state => {
    let acts = state.actions.filter(a => !a.deleted)
    const dayToSeconds = value => value * 86400
    const minutesToSeconds = value => value * 60
    const getDelay = act => dayToSeconds(act.action_data.options.days_after || 0) + minutesToSeconds(act.action_data.options.delay_minutes || 0)

    return acts.sort((a, b) => getDelay(a) - getDelay(b) || a.action_sequence - b.action_sequence)
}

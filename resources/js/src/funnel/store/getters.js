export const GetLeadStatusesForSelect = state => {
    let res = []
    state.leadStatuses.forEach(leadStatus => {
        res.push({
            id: leadStatus.id,
            text: lead_status.status
        })
    })
    return res
}

export const GetPostbackEventTypesForSelect = state => {
    let res = []
    let aux = state.postbackEventTypes.filter(pt => !state.steps.find(st => st.postback_event_type_id == pt.id))
    aux.forEach(postbackEventType => {
        res.push({
            id: postbackEventType.id,
            text: postbackEventType.postback_event_type
        })
    })
    return res
}

export const GetPostbackEventTypeById = state => id => {
    return state.postbackEventTypes.find(a => a.id === id)
}

export const GetStepByIndex = state => index => {
    return state.steps[index]
}

export const GetActionTypeByName = state => name => {
    return state.actionTypes.find(act => act.action_type_name === name)
}

export const GetActionTypeById = state => id => {
    return state.actiontypes.find(act => act.id === id)
}

export const OrderedSteps = state => {
    return state.steps.sort((a, b) => a.funnel_step_sequence - b.funnel_step_sequence)
}

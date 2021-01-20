export const GetRemarketingFunnelsForSelect = (state, getters, rootState) => {
    let res = []
    state.remarketingFunnels.filter(f => f.id != rootState.funnel.funnelId).forEach(funnel => {
        res.push({
            id: funnel.id,
            text: funnel.funnel_description
        })
    })

    return res
}

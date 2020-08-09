import * as componentTypes from '../../action/component-types'

export const setupState = () => {
    return {
        funnel: null,
        steps: [],
        leads: [],
        currentSchedule: null
    }
}

export default setupState()

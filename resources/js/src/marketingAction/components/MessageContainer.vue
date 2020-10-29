<template>
    <transition enter-active-class="animate__animated animate__fadeIn animate__fast" leave-active-class="animate__animated animate__fadeOut animate__faster" mode="out-in">
        <component :is="CurrentMessageComponent"></component>
    </transition>
</template>

<script>
import { mapState } from 'vuex'
import { ACTION } from '../../../user/constants'
import MessageChoice from './MessageChoice'
import MessageSMS from './MessageSMS'
import MessageEmail from './MessageEmail'
import MessageWhatsapp from './MessageWhatsapp'

const MSG_COMPONENTS = {
    SMS: 'MessageSMS',
    EMAIL: 'MessageEmail',
    WHATSAPP: 'MessageWhatsapp',
    NONE: 'MessageChoice',
}

export default {
    components: {
        MessageChoice,
        MessageSMS,
        MessageEmail,
        MessageWhatsapp
    },
    computed: {
        ...mapState('marketingAction', [
            'messageType'
        ]),
        CurrentMessageComponent() {
            switch (this.messageType) {
                case ACTION.SMS:
                    return MSG_COMPONENTS.SMS
                    break;

                case ACTION.EMAIL:
                    return MSG_COMPONENTS.EMAIL
                    break;

                case ACTION.WHATSAPP:
                    return MSG_COMPONENTS.WHATSAPP
                    break;

                default:
                    return  MSG_COMPONENTS.NONE
                    break;
            }
        }
    },
}
</script>

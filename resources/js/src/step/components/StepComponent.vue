<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            Ações a serem executadas
        </div>
        <div v-for="(groupActions, index) in groupedActions" :key="index" class="row" style="border-bottom: 1px solid rgba(34, 41, 47, 0.125)">
            <div class="col-3 bg-warning d-flex justify-content-center align-items-center text-white">
                <strong>{{ groupActions[0].action_data.options.days_after == 0 ? 'No mesmo dia' : `Após ${groupActions[0].action_data.options.days_after} dia(s)` }}</strong>
            </div>
            <div class="col-9 pl-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-for="item in groupActions" :key="item.id">
                        <div class="row">
                            <div class="col-4">
                                <i :class="getActionIcon(item)"></i> {{ item.action_description }}
                            </div>
                            <div class="col-4">
                                <template v-if="item.action_data.options.hasOwnProperty('extra') && item.action_data.options.extra.qualquer_horario">
                                    Qualquer horário
                                </template>
                                <template v-else>
                                    Entre {{ item.action_data.options.start_time }} e {{ item.action_data.options.end_time }} horas
                                </template>
                            </div>
                            <div class="col-4">
                                <template v-if="item.action_data.options.delay_minutes > 0">
                                    Aguardar {{ item.action_data.options.delay_minutes }} minutos...
                                </template>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
        <!-- <ul class="list-group list-group-flush">
            <li class="list-group-item p-0" v-for="(groupActions, index) in groupedActions" :key="index">
                <div class="d-flex">
                    <div class="bg-warning p-2 d-flex justify-content-center align-items-center mr-1">{{ groupActions[0].action_data.options.days_after }} Dias</div>
                    <div class="flex-grow-1">
                        <div v-for="item in groupActions" :key="item.id">
                            <i :class="getActionIcon(item)"></i> {{ item.action_description }}
                        </div>
                    </div>
                </div>
            </li>
        </ul> -->
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <button class="btn btn-warning float-right" @click="editStep"><i class="fa fa-edit"></i> Alterar Evento</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
            ...this.step,
        }
    },
    computed: {
        ...mapState('funnel', [
            'actionTypes'
        ]),
        groupedActions() {
            return _.groupBy(this.orderedActions, 'action_data.options.days_after')
        },
        execAfter() {
            let dia = this.delay_days == 1 ? 'dia' : 'dias'
            let hora= this.delay_hours == 1 ? 'hora' : 'horas'
            return `${this.delay_days} ${dia} e ${this.delay_hours} ${hora}`
        },
        /* ...mapGetters('step', [
            'OrderedActions'
        ]) */
        orderedActions() {
           /*  let acts = this.actions.filter(a => !a.deleted)
            return acts.sort((a, b) => a.action_sequence - b.action_sequence) */
            let acts = this.actions.filter(a => !a.deleted)
            const dayToSeconds = value => value * 86400
            const minutesToSeconds = value => value * 60
            const getDelay = act => dayToSeconds(act.action_data.options.days_after || 0) + minutesToSeconds(act.action_data.options.delay_minutes || 0)

            return acts.sort((a, b) => getDelay(a) - getDelay(b) || a.action_sequence - b.action_sequence)
        }
    },
    props: {
        step: {
            type: Object,
            required: true
        }
    },
    methods: {
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionIsEditingStep'
        ]),
        ...mapActions('step', [
            'ActionLoadStep',
            'ActionClearState'
        ]),
        editStep() {
            this.ActionClearState()
                .then (() => {
                    this.ActionLoadStep()
                        .then(() => {
                            this.ActionIsEditingStep(true)
                            this.ActionSetShowCrudStep(true)
                        })
                })
        },
        getActionIcon(item) {
            let act = this.actionTypes.find(a => a.id === item.action_type_id)
            return {
               'fas fa-envelope fa-2x': act.action_type_name == 'email',
               'fas fa-sms fa-2x': act.action_type_name == 'sms',
               'fab fa-whatsapp fa-2x': act.action_type_name == 'whatsapp',
               'fas fa-filter fa-2x': act.action_type_name == 'funnel',
            }
        }
    }
}
</script>

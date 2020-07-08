<template>
    <div>
        <div class="card mb-1">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <label for="original_tag">Executar Após</label>
                        <div class="form-control">{{ execAfter }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="delay">Nova Tag</label>
                        <div class="form-control">{{ newTagDescription }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-primary text-white p-1">
                Ações a serem executadas
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item" v-for="(item, index) in OrderedListActions" :key="index">
                    <i class="fas fa-2x" :class="getActionIcon(item)"></i> {{ item.action_description }}
                </li>
            </ul>
            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-warning float-right"><i class="fa fa-edit"></i> Alterar Passo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex'

export default {
    data() {
        return {
            ...this.step
        }
    },
    computed: {
        ...mapGetters('funnel', [
            'GetTagById'
        ]),
        ...mapState('funnel', [
            'actionTypes'
        ]),
        ...mapGetters('steps', [
            'OrderedListActions'
        ]),
        execAfter() {
            let dia = this.delay_days == 1 ? 'dia' : 'dias'
            let hora= this.delay_hours == 1 ? 'hora' : 'horas'
            return `${this.delay_days} ${dia} e ${this.delay_hours} ${hora}`
        },
        newTagDescription() {
            let tag = this.GetTagById(this.step.new_tag_id)
            return  tag ? tag.tag_name : 'Nenhuma Tag informada'
        }
    },
    props: {
        step: {
            type: Object,
            required: true
        }
    },
    methods: {
        getActionIcon(item) {
            let act = this.actionTypes.find(a => a.id === item.action_type_id)
            return {
               'fa-envelope': act.action_type_name == 'email',
               'fa-sms': act.action_type_name == 'sms',
            }
        }
    }
}
</script>

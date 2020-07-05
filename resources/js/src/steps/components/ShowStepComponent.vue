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
                <li class="list-group-item" v-for="(item, index) in actions" :key="index">
                    <i class="fas fa-2x" :class="{'fa-envelope': item.actionType.action_type_name == 'email', 'fa-sms': item.actionType.action_type_name == 'sms'}"></i> {{ item.description }}
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
import { mapGetters } from 'vuex'

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
        execAfter() {
            let dia = this.data.delayDays == 1 ? 'dia' : 'dias'
            let hora= this.data.delayHours == 1 ? 'hora' : 'horas'
            return `${this.data.delayDays} ${dia} e ${this.data.delayHours} ${hora}`
        },
        newTagDescription() {
            let tag = this.GetTagById(this.step.data.newTag)
            return  tag ? tag.tag_name : 'Nenhuma Tag informada'
        }
    },
    props: {
        step: {
            type: Object,
            required: true
        }
    },
}
</script>

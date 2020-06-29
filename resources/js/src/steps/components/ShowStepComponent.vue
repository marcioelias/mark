<template>
    <div>
        <div class="card mb-1">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-4">
                        <label for="original_tag">Tag Atual</label>
                        <div class="form-control">Tag 1</div>
                    </div>
                    <div class="col-md-4">
                        <label for="new_tag">Nova Tag</label>
                        <div class="form-control">Tag 2</div>
                    </div>
                    <div class="col-md-4">
                        <label for="delay">Executar após</label>
                        <div class="form-control">1 Dia e 2 Horas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-primary text-white p-1">
                Ações a serem executadas
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item" v-for="(item, index) in data" :key="index">
                    <i class="fas fa-2x" :class="{'fa-envelope': item.type == 'email', 'fa-sms': item.type == 'sms'}"></i> {{ item.description }}
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
export default {
    data() {
        return {
            ...this.step
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

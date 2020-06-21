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
        <div class="card">
            <div class="card-header bg-primary text-white p-1">
                Ações a serem executadas
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><i class="fa fa-at fa-2x"></i> Enviar E-mail</li>
                <li class="list-group-item"><i class="fas fa-sms fa-2x"></i> Enviar SMS</li>
            </ul>
        </div>
        <div class="row">
            <div class="col">
                <button class="btn btn-warning float-right"><i class="fa fa-edit"></i> Alterar</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: [
                {
                    id: 1,
                    name: 'Enviar SMS'
                },
                {
                    id: 2,
                    name: 'Enviar E-mail'
                }
            ]
        }
    },
}
</script>

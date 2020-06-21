<template>
    <div>
        <div class="card mb-1">
            <div class="card-header bg-primary text-white p-1">
                Configurações
            </div>
            <div class="card-body pl-0 pr-0">
                <div class="row">
                    <div class="col-md-3">
                        <label for="original_tag">Tag Atual</label>
                        <Select2 v-model="originalTag" name="original_tag" id="original_tag" :setting="{dropdownCssClass: 'form-control'}" :options="GetTagsForSelect" />
                    </div>
                    <div class="col-md-3">
                        <label for="new_tag">Nova Tag</label>
                        <Select2 v-model="newTag" name="new_tag" id="new_tag" :options="GetNewTagsForSelect" />
                    </div>
                    <div class="col-md-3">
                        <label for="delay_days">Executar Após (dias)</label>
                        <input type="number" name="dalay_days" id="dalay_days" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="delay_hours">Executar Após (horas)</label>
                        <input type="number" name="dalay_hours" id="dalay_hours" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white p-1 d-flex justify-content-between align-items-center">
                <div>Ações</div>
                <button class="btn btn-success">
                    <i class="fa fa-plus"></i> Nova Ação
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover bg-white">
                    <thead>
                        <tr class="bg-light text-dark">
                            <th class="p-n1">
                                Ação
                            </th>
                            <th class="p-n1">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data" :key="item.id">
                            <td class="align-middle" scope="row">
                                {{ item.name }}
                            </td>
                            <td class="align-middle text-center" scope="row">
                                <span data-toggle="tooltip" data-placement="top" title="Editar">
                                    <a href="#" class="btn btn-sm btn-outline-primary waves-effect waves-light"><i class="fa fa-edit" style="font-size: 1.2rem"></i></a>
                                </span>
                                <span data-toggle="tooltip" data-placement="top" title="Remover">
                                    <a href="#" class="btn btn-sm btn-outline-danger waves-effect waves-light"><i class="fa fa-trash" style="font-size: 1.2rem"></i></a>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button class="btn btn-success float-right"><i class="fas fa-plus"></i> Salvar passo</button>
            </div>
        </div>
    </div>
</template>

<script>
import Select2 from 'v-select2-component';
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            tags: ['tag 1', 'tag 2', 'tag 3', 'tag 4', 'tag 5'],
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
    components: {
        Select2
    },
    computed: {
        ...mapGetters('funnel', [
            'GetTagsForSelect', 'GetNewTagsForSelect'
        ]),
        originalTag: {
            get() {
                return this.$store.state.funnel.originalTag
            },
            set(value) {
                this.$store.dispatch('funnel/ActionSetOriginalTag', value)
            }
        },
        newTag: {
            get() {
                return this.$store.state.funnel.newTag
            },
            set(value) {
                this.$store.dispatch('funnel/ActionSetNewTag', value)
            }
        }
    },
    mounted() {
        this.$store.dispatch('funnel/ActionGetTags', { vm: this })
    }
}
</script>

<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fas fa-envelope fa-2x"></i> Nova mensagem de E-mail
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="row mb-1">
                <div class="col">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="emailAction.description" placeholder="Exemplo: Enviar SMS">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="email-message-editor">Texto do E-mail</label>
                    <quill-editor ref="emailMessageEditor" id="email-message-editor" v-model="emailAction.actionData.data" :options="variablesDWConfig"></quill-editor>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <hr />
                    <fieldset>
                        <label>Enviar somente no horário entre:</label>
                        <div class="d-flex justify-content-between">
                            <div class="form-control mr-1" style="width: 7rem">{{ emailAction.actionData.options.period[0] }} Horas</div>
                            <div class="flex-grow-1"><vs-slider step=1 :min=0 :max=23 text-fixed="horas" v-model="emailAction.actionData.options.period"/></div>
                            <div class="form-control ml-1" style="width: 7rem">{{ emailAction.actionData.options.period[1] }} Horas</div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <button class="btn btn-secondary float-right" @click="cancelNewEmailAction"><i class="fas fa-times"></i> Cancelar</button>
                    <button class="btn btn-success float-right mr-1" @click="saveEmailAction"><i class="fas fa-save"></i> Salvar mensagem</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { quillEditor } from 'vue-quill-editor'
import { mapState, mapActions, mapGetters } from 'vuex'
import * as componentTypes from '../../steps/components/component-types'

const iniData = {
    id: null,
    description: 'Enviar Email',
    actionData: {
        data: '',
        options: {
            period: [0,23]
        }
    },
    isEditing: false,
    actionType: null
}

export default {
    data() {
        return {
            emailAction: { ...iniData },
            variablesDWConfig: {
                placeholder: 'Digite a mensagem a ser enviada...',
                theme: 'snow'
            }
        }
    },
    components: {
        quillEditor
    },
    computed: {
        ...mapState('steps', [
            'isEditing', 'editingIndex'
        ]),
        ...mapGetters('variables', [
            'GetVariablesAsObject'
        ]),
        ...mapGetters('steps', [
            'GetActionByIndex'
        ]),
        ...mapGetters('funnel', [
            'GetActionTypeByName'
        ])
    },
    methods: {
        ...mapActions('steps', [
            'ActionSetActiveComponent', 'ActionSetNewAction', 'ActionSetUpdateAction'
        ]),
        saveEmailAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
            if (this.isEditing) {
                this.ActionSetUpdateAction(this.emailAction)
            } else {
                this.ActionSetNewAction(this.emailAction)
            }
        },
        cancelNewEmailAction() {
            this.$swal.fire({
                    title: 'Cancelar cadastro da ação?',
                    text: `Os dados informados serão perdidos...`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, Cancelar!',
                    cancelButtonText: 'Não, Continuar.'
                }).then(result => {
                    if (result.value) {
                        this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
                    }
                })
        },
        addCustomSelectToEditor() {
            const quill = this.$refs.emailMessageEditor.quill

            const variablesDW = new QuillToolbarDropDown({
                label: "Variáveis",
                rememberSelection: false
            })

            variablesDW.setItems(this.GetVariablesAsObject)

            variablesDW.onSelect = function(label, value, quill) {
                const { index, length } = quill.selection.savedRange
                quill.deleteText(index, length)
                quill.insertText(index, value)
                quill.setSelection(index + value.length)
            }

            variablesDW.attach(quill)
        }
    },
    mounted() {
        this.addCustomSelectToEditor()
        this.emailAction.actionType = this.GetActionTypeByName('email')
        if (this.isEditing) {
            this.emailAction = { ...this.GetActionByIndex(this.editingIndex) }
        }
    }
}
</script>

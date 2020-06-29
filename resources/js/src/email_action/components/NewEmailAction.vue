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
            <!-- <div class="row mb-1">
                <div class="col">
                    <select-variables :component="'email_body'" />
                    <small><i class="fas fa-exclamation text-info"></i> Selecione variáveis para incluir dados pessoais, como Nome do Cliente, Link do Boleto, etc.</small>
                </div>
            </div> -->
            <div class="row">
                <div class="col">
                    <label for="exampleFormControlTextarea1">Texto do E-mail</label>
                    <froala :tag="'textarea'" :config="config" v-model="emailAction.emailMessage"></froala>
                    <!-- <textarea class="form-control" ref="email_body" id="exampleFormControlTextarea1" rows="3" placeholder="Digite o texto para a mensagem SMS..." v-model="emailAction.textMessage"></textarea> -->
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
import VueFroala from 'vue-froala-wysiwyg';
import { mapActions } from 'vuex'
import * as componentTypes from '../../steps/components/component-types'

const iniData = {
    id: null,
    type: 'email',
    description: 'Enviar Email',
    emailMessage: '',
}

const toolBtns = {
    'moreText': {
        'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
    },
    'moreParagraph': {
        'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
    },
    'moreRich': {
        'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
    },
    'moreMisc': {
        'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help'],
        'align': 'right',
        'buttonsVisible': 2
    }
}

export default {
    data() {
        return {
            emailAction: { ...iniData },
            config: {
                toolbarButtons: { ...toolBtns },
                toolbarButtonsMD: { ...toolBtns },
                toolbarButtonsSM: { ...toolBtns },
                toolbarButtonsXS: { ...toolBtns }
            }
        }
    },
    methods: {
        ...mapActions('steps', [
            'ActionSetActiveComponent'
        ]),
        saveEmailAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
           /*  if (this.isEditing) {
                this.ActionSetUpdateAction(this.emailAction)
            } else {
                this.ActionSetNewAction(this.emailAction)
            } */
        },
        cancelNewEmailAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
        },
    }
}
</script>

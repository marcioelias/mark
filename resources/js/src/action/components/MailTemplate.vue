<template>
	<div class="dropdown">
		<button
			class="btn btn-success dropdown-toggle"
			style="padding: 0.9rem 2rem"
			type="button"
			id="dropDownTemplates"
			data-toggle="dropdown"
			aria-haspopup="true"
			aria-expanded="false"
		>Templates</button>
		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropDownTemplates">
			<a class="dropdown-item" @click="loadTemplateDialog">
				<i class="fa fa-upload"></i> Carregar Template
			</a>
			<a class="dropdown-item" @click="saveTemplateDialog">
				<i class="fas fa-save"></i> Salvar como Template
			</a>
		</div>
	</div>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
            required: true,
            default: ''
        }
    },
    methods: {
        saveTemplateDialog(templateName = '') {
            if (!this.message.trim()) {
                this.$swal.fire({
                    title: 'Oops...',
                    text: `Não é possível salvar um template em branco!`,
                    icon: 'info'
                })

                return
            }
            this.$swal.fire({
                    title: 'Salvar como Template',
                    text: `Infome um nome para este template`,
                    input: 'text',
                    inputValue: templateName,
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    cancelButtonText: 'Cancelar'
                }).then(async result => {
                    if (result.isConfirmed) {
                        this.templateExists(result.value)
                    }
                })
        },
        async loadTemplateDialog() {
            await this.$http.get('/mailtemplate/list')
                    .then((res) => {

                        let options = {}
                        res.data.forEach(mt => {
                            options[mt.id] = mt.template_name
                        });

                        this.$swal.fire({
                            title: 'Carregar template',
                            input: 'select',
                            inputOptions: { ...options },
                            heightAuto: false,
                            showCancelButton: true,
                            confirmButtonText: 'Carregar',
                            cancelButtonText: 'Cancelar'
                        }).then(result => {
                            if (result.isConfirmed) {
                                this.loadTemplateById(result.value)
                            }
                        })
                    })

        },
        async templateExists(templateName) {
            return await this.$http.post('/mailtemplate/exists', {
                    template_name: templateName
                })
                .then(res => {
                    if (res.data.exists) {
                        this.$swal.fire({
                            title: 'Oops...',
                            text: `Já existe um template com nome "${templateName}" cadastrado!`,
                            icon: 'error'
                        }).then(() => this.saveTemplateDialog(templateName))
                    } else {
                        this.storeTemplate(templateName)
                    }
                })
        },
        async storeTemplate(templateName) {
            await this.$http.post('/mailtemplate', {
                template_name: templateName,
                template: this.message
            })
            .then(res => {
                if (res.status === 200) {
                    this.$swal.fire({
                        title: 'Sucesso',
                        text: `Template "${res.data.template_name}" salvo!`,
                        icon: 'success'
                    })
                } else {
                    this.$swal.fire({
                        title: 'Oops...',
                        text: `Ocorreu um erro ao salvar o template!`,
                        icon: 'error'
                    })
                }
            })
        },
        async loadTemplateById(templateId) {
            await this.$http.get(`/mailtemplate/${templateId}`)
                    .then(res => {
                        this.$emit('templateLoaded', res.data.template)
                        this.$swal.fire({
                            title: 'Sucesso',
                            text: `Template "${res.data.template_name}" carregado com sucesso!`,
                            icon: 'success'
                        })
                    })
                    .catch(err => {
                        this.$swal.fire({
                            title: 'Oops...',
                            text: `Ocorreu um erro ao carregar o template!`,
                            icon: 'error'
                        })
                    })
        }
    }
}
</script>

<template>
    <div
		class="modal fade text-left"
		id="notificationSentModal"
		tabindex="-1"
		role="dialog"
		aria-labelledby="notificationSentModal"
		aria-hidden="true"
        ref="notificationSentModal"
	>

		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="notificationSentModal">
                        {{ title }}
                    </h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <transition enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut animate__faster" mode="out-in" apper>
                        <div v-if="isLoading" class="d-flex justify-content-center align-items-center p-2" key="loadingContent">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Carregando...</span>
                            </div>
                            <span class="pl-2">Carregando...</span>
                        </div>
                        <div v-else class="card mb-0" key="showingContent">
                            <div class="card-header d-block">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Agendado em:</label>
                                        <div class="form-control">
                                            {{ getQueuedAt }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Enviado em:</label>
                                        <div class="form-control">
                                            {{ getSentAt }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pb-1">
                                <template v-if="isEmailAction">
                                <div class="row mb-2">
                                    <div class="col">
                                        <label>Assunto</label>
                                        <div class="form-control">
                                            {{ subject }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label>Mensagem</label>
                                        <div class="form-control h-100 messageContent" v-html="message"></div>
                                    </div>
                                </div>
                                </template>
                                <div class="row" v-else>
                                    <div class="col">
                                        <label>Mensagem</label>
                                        <div class="form-control messageContent">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
</template>

<style>
    .messageContent {
        max-height: 40vh;
        min-height: 80px;
        overflow: auto;
    }
</style>

<script>
import { mapState, mapActions } from 'vuex'
import { ACTION } from "../../../user/constants"
import Moment from 'moment'

export default {
    data() {
        return {
            message: '',
            subject: '',
            isLoading: true
        }
    },
    watch: {
        currentSchedule(value) {
            if (value) {
                this.getParsedMessage()
            }
        }
    },
    methods: {
        ...mapActions('funnelShow', [
            'ActionSetCurrentSchedule'
        ]),
        async getParsedMessage() {
            this.isLoading = true
            await this.$http.get(`/message/${this.currentSchedule.funnel_step_action_id}/${this.currentSchedule.lead_id}`)
                            .then(res => {
                                this.message = res.data.message
                                this.subject = res.data.subject
                                this.isLoading = false
                            })
        }
    },
    computed: {
        ...mapState('funnelShow', [
            'currentSchedule'
        ]),
        title() {
            return this.currentSchedule ? this.currentSchedule.action.action_description : ''
        },
        content() {
            return this.currentSchedule ? JSON.parse(this.currentSchedule.action.action_data).data : ''
        },
        isEmailAction() {
            return this.currentSchedule && this.currentSchedule.action.action_type_id === ACTION.EMAIL
        },
        getQueuedAt() {
            return this.currentSchedule ? Moment(String(this.currentSchedule.queued_at)).format('DD/MM/YYYY hh:mm:ss') : ''
        },
        getSentAt() {
            return this.currentSchedule ? this.currentSchedule.finished_at ? Moment(String(this.currentSchedule.finished_at)).format('DD/MM/YYYY hh:mm:ss') : 'Não enviada' : 'Não enviada'
        }
    },
    mounted() {
        $('#notificationSentModal').on('hidden.bs.modal', (e) => {
            this.ActionSetCurrentSchedule(null)
        })

    }
}
</script>

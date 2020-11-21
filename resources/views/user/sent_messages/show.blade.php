@extends('layouts.contentLayoutMaster')

@section('title', "Envio de Mensagem")

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="card-title">Ação {{ $sentMessage->funnelStepAction->actionType->action_type_description }}</div>
            <a href="{{ route('sent_message.index') }}" class="btn btn-primary">Voltar</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Cliente</label>
                    <div class="form-control d-table">{{ $sentMessage->lead->customer->customer_name }}</div>
                </div>
                @if($sentMessage->funnelStepAction->action_type_id == \App\Constants\ActionTypes::EMAIL)
                <div class="col-md-6">
                    <label>E-mail</label>
                    <div class="form-control d-table">{{ $sentMessage->lead->customer->customer_email }}</div>
                </div>
                @else
                <div class="col-md-6">
                    <label>Telefone</label>
                    <div class="form-control d-table">{{ $sentMessage->lead->customer->customer_phone_number }}</div>
                </div>
                @endif
            </div>
            {{-- @if($sentMessage->funnelStepAction->action_type_id == \App\Constants\ActionTypes::EMAIL)
            <div class="row mt-1">
                <div class="col">
                    <label>Assunto</label>
                    <div class="form-control d-table">{!! $sentMessage->message_data !!}</div>
                </div>
            </div>
            @endif --}}
            <div class="row mt-1">
                <div class="col">
                    <label>Mensagem</label>
                    <div class="form-control d-table">{!! $sentMessage->message_data !!}</div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-4">
                    <label>Produto</label>
                    <div class="form-control d-table">{{ $sentMessage->lead->product->product_name }}</div>
                </div>
                <div class="col-md-4">
                    <label>Funil</label>
                    <div class="form-control d-table">{{ $sentMessage->funnelStepAction->funnelStep->funnel->funnel_description }}</div>
                </div>
                <div class="col-md-4">
                    <label>Evento</label>
                    <div class="form-control d-table">{{ $sentMessage->funnelStepAction->funnelStep->postbackEventType->postback_event_type }}</div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-4">
                    <label>Evento disparado em:</label>
                    <div class="form-control d-table">{{ $sentMessage->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
                    <div class="form-control d-table">
                        {{ $sentMessage->is_successful ? 'Enviado com Sucesso' : 'Não foi possível enviar' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Mensagem retornada</label>
                    <div class="form-control d-table">
                        {{ $sentMessage->return_data }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
@endsection

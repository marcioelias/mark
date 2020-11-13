@extends('layouts.contentLayoutMaster')

@section('title', "Visualização de Ação de Marketing")

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ $marketingAction->marketing_action_description }}</div>
            <a href="{{ route('marketing_action.index') }}" class="btn btn-primary">Voltar</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Produto</label>
                    <div class="form-control">{{ $marketingAction->product->product_name }}</div>
                </div>
                <div class="col-md-6">
                    <label for="">Início do Disparo</label>
                    <div class="form-control">{{ $marketingAction->start_at }}</div>
                </div>
            </div>
            <div class="divider divider-primary divider-dotted">
                <div class="divider-text">Mensagem ({{ $marketingAction->actionType->action_type_name }})</div>
            </div>
            @if($marketingAction->action_type_id == \App\Constants\ActionTypes::EMAIL)
            <div class="row">
                <div class="col">
                    <h4>Assunto: {!! $marketingAction->action_message["options"]["subject"] !!}</h4>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col">
                    <div class="form-control">{!! $marketingAction->action_message["data"] !!}</div>
                </div>
            </div>
            <div class="divider divider-primary divider-dotted">
                <div class="divider-text">Clientes</div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped bg-white mb-0">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>Cliente</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Status (Cliente)</th>
                            <th>Status (Envio)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marketingAction->customers as $customer)
                        <tr>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->customer_email }}</td>
                            <td>{{ $customer->customer_phone_number }}</td>
                            <td>{{ $customer->customerStatus->customer_status }}</td>
                            <td class="{{ $customer->pivot->result_ok ? 'bg-success' : 'bg-danger' }} text-white">
                                <i class="fas fa-info-circle" data-toggle="tooltip"
                                data-html="true"
                                title="<strong class='mb-1'>{{ $customer->pivot->result_message }}</strong><hr size='1' class='bg-white p-0 my-50' /><i class='fas fa-clock mr-1'></i> {{ Carbon\Carbon::parse($customer->pivot->schedule_date)->format('d/m/Y H:i:s') }} <br class='p-1' />
                                {{ $customer->pivot->result_ok ? '<i class=\'fas fa-paper-plane mr-1\'></i>' : '<i class=\'fas fa-times mr-1\'></i>' }} {{ Carbon\Carbon::parse($customer->pivot->finished_at)->format('d/m/Y H:i:s') }}"></i>
                                {{ $customer->pivot->result_message }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
@endsection

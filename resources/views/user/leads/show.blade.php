@extends('layouts.contentLayoutMaster')

@section('title', "Lead (Transação: $lead->transaction_code)")

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="card-title">Lead</div>
            <a href="{{ route('lead.index') }}" class="btn btn-primary">Voltar</a>
        </div>
        <div class="card-body">
            <div class="row mb-1">
                <div class="col">
                    <label for="">ID</label>
                    <div class="form-control">{{ $lead->id }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="">Transação</label>
                    <div class="form-control">{{ $lead->transaction_code }}</div>
                </div>
                <div class="col-md-4">
                    <label for="">Data</label>
                    <div class="form-control">{{ $lead->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="col-md-4">
                    <label for="">Plataforma</label>
                    <div class="form-control">{{ $lead->product->plataformConfig->plataform->plataform_name }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card mb-0">
                        <div class="card-header pl-0 pr-0">
                            <div class="card-title">Produto</div>
                        </div>
                        <div class="card-body pl-0 pr-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Descrição</label>
                                    <div class="form-control">{{ $lead->product->product_name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Código na Plataforma</label>
                                    <div class="form-control">{{ $lead->product->plataform_code }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card mb-0">
                        <div class="card-header pl-0 pr-0">
                            <div class="card-title">Cliente</div>
                        </div>
                        <div class="card-body pl-0 pr-0">
                            <div class="row mb-1">
                                <div class="col">
                                    <label for="">Nome</label>
                                    <div class="form-control">{{ $lead->customer->customer_name }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Telefone</label>
                                    <div class="form-control">{{ $lead->customer->customer_phone_number }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">E-mail</label>
                                    <div class="form-control">{{ $lead->customer->customer_email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
@endsection

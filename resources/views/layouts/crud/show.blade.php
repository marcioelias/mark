@extends('layouts.contentLayoutMaster')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-body">
        @yield('show-content')
    </div>
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ $route }}" class="btn btn-primary">
            <i class="fa fa-times"></i> Fechar Visualização
        </a>
    </div>
</div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/scripts/jquery.mask.min.js') }}"></script>
@endsection

@push('document-ready')
$('.date').mask('00/00/0000');
$('.time').mask('00:00:00');
$('.date_time').mask('00/00/0000 00:00:00');
$('.cep').mask('00000-000');
$('.phone').mask('0000-0000');
$('.phone_with_ddd').mask('(00) 00000-0000');
$('.phone_us').mask('(000) 000-0000');
$('.mixed').mask('AAA 000-S0S');
$('.cpf').mask('000.000.000-00', {reverse: true});
$('.cnpj').mask('00.000.000/0000-00', {reverse: true});
$('.money').mask('000000000000000.00', {reverse: true});
@endpush

@extends('layouts.contentLayoutMaster')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-body">
        @component('components.form', [
        'routeUrl' => $route,
        'method' => $method ?? 'PATCH',
        'redirect' => $redirect,
        'formButtons' => $buttons ?? [
            [
                'type' => 'submit',
                'label' => 'Save',
                'icon' => 'check'
            ],
            [
                'type' => 'button',
                'label' => 'Cancel',
                'icon' => 'times'
            ]]
        ])
        @section('formFields')
            @yield('create-form')
        @endsection
    @endcomponent
    </div>
</div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/scripts/jquery.mask.min.js') }}"></script>
    @stack('custom-script')
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

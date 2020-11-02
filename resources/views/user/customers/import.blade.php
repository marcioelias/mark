@extends('layouts.crud.vue', [
    'title' => 'Importar Clientes',
    'route' => route('customers.upload'),
    'redirect' => route('customer.index')
])

@section('vue-component')
    <div id="customers-import">
        <form-component />
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/user/customersImport.js') }}"></script>
@endsection

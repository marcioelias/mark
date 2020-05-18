@extends('layouts.contentLayoutMaster')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-body">
        @component('components.form', [
        'routeUrl' => $route,
        'method' => $method ?? 'POST',
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
@endsection

@extends('layouts.crud.create', [
    'title' => 'Novo Status de Cliente',
    'route' => route('customer_status.store'),
    'redirect' => route('customer_status.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'customer_status',
                'label' => 'Status',
                'required' => true,
                'inputSize' => 12
            ]
        ]
    ])
    @endcomponent
@endsection

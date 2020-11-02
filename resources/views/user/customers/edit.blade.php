@extends('layouts.crud.edit', [
    'title' => 'Alterar Cliente',
    'route' => route('customer.update', $customer->id),
    'redirect' => route('customer.index')
])

@section('create-form')
@component('components.form-group', [
    'inputs' => [
        [
            'type' => 'text',
            'field' => 'customer_name',
            'label' => 'Nome',
            'required' => true,
            'inputSize' => 12,
            'inputValue' => $customer->customer_name
        ]
    ]
])
@endcomponent
@component('components.form-group', [
    'inputs' => [
        [
            'type' => 'text',
            'field' => 'customer_phone_number',
            'label' => 'Telefone',
            'required' => true,
            'inputSize' => 6,
            'icon' => [
                'side' => 'left',
                'type' => 'phone',
                'divider' => true
            ],
            'inputValue' => $customer->customer_phone_number
        ],
        [
            'type' => 'text',
            'field' => 'customer_email',
            'label' => 'E-mail',
            'required' => true,
            'inputSize' => 6,
            'icon' => [
                'side' => 'left',
                'type' => 'at-sign',
                'divider' => true
            ],
            'inputValue' => $customer->customer_email
        ],
    ]
])
@endcomponent
@endsection

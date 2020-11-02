@extends('layouts.crud.create', [
    'title' => 'Novo Cliente',
    'route' => route('customer.store'),
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
                'inputSize' => 12
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
                ]
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
                ]
            ],
        ]
    ])
    @endcomponent
@endsection

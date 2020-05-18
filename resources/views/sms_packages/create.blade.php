@extends('layouts.crud.create', [
    'title' => 'Novo Pacote',
    'route' => route('sms_package.store'),
    'redirect' => route('sms_package.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'sms_package_name',
                'label' => 'Pacote',
                'required' => true,
                'inputSize' => 10
            ],
            [
                'type' => 'checkbox',
                'field' => 'active',
                'label' => 'Ativo',
                'checked' => true,
                'required' => true,
                'inputSize' => 2
            ]
        ]
    ])
    @endcomponent
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'number',
                'field' => 'sms_amount',
                'label' => 'Quantidade de SMS',
                'required' => true,
                'inputSize' => 6
            ],
            [
                'type' => 'number',
                'field' => 'package_value',
                'label' => 'Valor',
                'required' => true,
                'inputSize' => 6,
                'icon' => [
                    'side' => 'left',
                    'type' => 'dollar-sign',
                    'divider' => true
                ]
            ]
        ]
    ])
    @endcomponent
@endsection

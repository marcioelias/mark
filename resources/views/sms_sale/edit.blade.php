@extends('layouts.crud.edit', [
    'title' => 'Alterar Pacote',
    'route' => route('sms_package.update', $smsPackage->id),
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
            'inputSize' => 10,
            'inputValue' => $smsPackage->sms_package_name
        ],
        [
            'type' => 'checkbox',
            'field' => 'active',
            'label' => 'Ativo',
            'required' => true,
            'inputSize' => 2,
            'checked' => $smsPackage->active
        ]
    ]
])
@endcomponent
@component('components.form-group', [
        'inputs' => [
            [
                'type' => 'textarea',
                'field' => 'sms_package_description',
                'label' => 'Descrição',
                'required' => true,
                'inputValue' => $smsPackage->sms_package_description
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
            'inputSize' => 6,
            'inputValue' => $smsPackage->sms_amount
        ],
        [
            'type' => 'text',
            'field' => 'package_value',
            'label' => 'Valor',
            'css' => 'money',
            'required' => true,
            'inputSize' => 6,
            'icon' => [
                'side' => 'left',
                'type' => 'dollar-sign',
                'divider' => true
            ],
            'inputValue' => $smsPackage->package_value
        ]
    ]
])
@endcomponent
@endsection

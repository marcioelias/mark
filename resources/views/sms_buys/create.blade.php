@extends('layouts.crud.create', [
    'title' => 'Nova Compra',
    'route' => route('sms_buy.store'),
    'redirect' => route('sms_buy.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'number',
                'field' => 'amount',
                'label' => 'Quantidade',
                'required' => true,
                'inputSize' => 6
            ],
            [
                'type' => 'number',
                'field' => 'unitary_value',
                'label' => 'Valor Unitário',
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

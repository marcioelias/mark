@extends('layouts.crud.create', [
    'title' => 'Novo Plano',
    'route' => route('plan.store'),
    'redirect' => route('plan.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'plan_name',
                'label' => 'Plano',
                'required' => true,
                'inputSize' => 6
            ],
            [
                'type' => 'text',
                'field' => 'marketplace_code',
                'label' => 'Código Marketplace',
                'required' => true,
                'inputSize' => 6
            ]
        ]
    ])
    @endcomponent
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'number',
                'field' => 'num_postbacks',
                'label' => 'Núm. Postbacks',
                'required' => true,
                'inputSize' => 6
            ],
            [
                'type' => 'number',
                'field' => 'plan_value',
                'label' => 'Valor',
                'required' => true,
                'inputSize' => 6,
                'icon' => [
                    'side' => 'left',
                    'type' => 'dollar-sign',
                    'divider' => true
                ]
            ],
        ]
    ])
    @endcomponent
@endsection

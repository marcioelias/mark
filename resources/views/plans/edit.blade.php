@extends('layouts.crud.edit', [
    'title' => 'Alterar Plano',
    'route' => route('plan.update', $plan->id),
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
                'inputSize' => 6,
                'inputValue' => $plan->plan_name
            ],
            [
                'type' => 'text',
                'field' => 'marketplace_code',
                'label' => 'Código Marketplace',
                'required' => true,
                'inputSize' => 6,
                'inputValue' => $plan->marketplace_code
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
                'inputSize' => 6,
                'inputValue' => $plan->num_postbacks
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
                ],
                'inputValue' => $plan->value
            ],
        ]
    ])
    @endcomponent
@endsection

@extends('layouts.crud.create', [
    'title' => 'Nova Ação de Marketing',
    'route' => route('marketing_action.store'),
    'redirect' => route('marketing_action.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'marketing_action_description',
                'label' => 'Descrição',
                'required' => true,
                'inputSize' => 4
            ],
            [
                'type' => 'select',
                'field' => 'product_id',
                'label' => 'Produto',
                'items' => $products,
                'displayField' => 'product_name',
                'keyField' => 'id',
                'inputSize' => 4
            ],
            [
                'type' => 'select',
                'field' => 'funnel_id',
                'label' => 'Funil',
                'items' => $funnels,
                'displayField' => 'funnel_description',
                'keyField' => 'id',
                'inputSize' => 4
            ],
        ]
    ])
    @endcomponent
@endsection

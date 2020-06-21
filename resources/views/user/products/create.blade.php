@extends('layouts.crud.create', [
    'title' => 'Novo Produto',
    'route' => route('product.store'),
    'redirect' => route('product.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'product_name',
                'label' => 'Produto',
                'required' => true,
                'inputSize' => 6
            ],
            [
                'type' => 'text',
                'field' => 'product_price',
                'label' => 'Preço',
                'required' => true,
                'inputSize' => 4,
                'css' => 'money',
                'icon' => [
                    'side' => 'left',
                    'type' => 'dollar-sign',
                    'divider' => true
                ]
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
                'type' => 'select',
                'field' => 'plataform_config_id',
                'label' => 'Plataforma',
                'items' => $plataforms,
                'displayField' => 'plataform_name',
                'keyField' => 'id',
                'inputSize' => 6
            ],
            [
                'type' => 'text',
                'field' => 'plataform_code',
                'label' => 'Código',
                'required' => true,
                'inputSize' => 6
            ],
        ]
    ])
    @endcomponent
@endsection

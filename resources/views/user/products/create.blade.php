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
                'inputSize' => 4
            ],
            [
                'type' => 'text',
                'field' => 'plataform_code',
                'label' => 'Código',
                'required' => true,
                'inputSize' => 4
            ],
            [
                'type' => 'select',
                'field' => 'funnel_id',
                'label' => 'Funil de Vendas',
                'items' => $funnels,
                'displayField' => 'funnel_description',
                'keyField' => 'id',
                'inputSize' => 4
            ]
        ]
    ])
    @endcomponent
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'email_from_name',
                'label' => 'Nome Rementente',
                'required' => true,
                'inputSize' => 6
            ],
        ],
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'email',
                'label' => 'E-mail',
                'required' => true,
                'inputSize' => 6
            ],
        ]
    ])
    @endcomponent
@endsection

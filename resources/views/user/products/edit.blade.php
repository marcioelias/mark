@extends('layouts.crud.edit', [
    'title' => 'Alterar Produto',
    'route' => route('product.update', $product->id),
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
                'inputSize' => 6,
                'inputValue' => $product->product_name
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
                ],
                'inputValue' => $product->product_price
            ],
            [
                'type' => 'checkbox',
                'field' => 'active',
                'label' => 'Ativo',
                'checked' => true,
                'required' => true,
                'inputSize' => 2,
                'checked' => $product->active
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
                'inputSize' => 4,
                'indexSelected' => $product->plataform_config_id
            ],
            [
                'type' => 'text',
                'field' => 'plataform_code',
                'label' => 'Código',
                'required' => true,
                'inputSize' => 4,
                'inputValue' => $product->plataform_code
            ],
            [
                'type' => 'select',
                'field' => 'funnel_id',
                'label' => 'Funil de Vendas',
                'items' => $funnels,
                'displayField' => 'funnel_description',
                'keyField' => 'id',
                'inputSize' => 4,
                'indexSelected' => $product->funnel_id
            ]
        ]
    ])
    @endcomponent
@endsection

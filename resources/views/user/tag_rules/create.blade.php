@extends('layouts.crud.create', [
    'title' => 'Nova Regra',
    'route' => route('tag_rule.store'),
    'redirect' => route('tag_rule.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'select',
                'field' => 'product_id',
                'label' => 'Produto',
                'items' => $products,
                'displayField' => 'product_name',
                'keyField' => 'id',
                'inputSize' => 5
            ],
            [
                'type' => 'select',
                'field' => 'lead_status_id',
                'label' => 'Status do Lead',
                'items' => $leadStatuses,
                'displayField' => 'status',
                'keyField' => 'id',
                'inputSize' => 5
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
                'field' => 'tag_id',
                'label' => 'Tag',
                'items' => $tags,
                'displayField' => 'tag_name',
                'keyField' => 'id',
                'inputSize' => 5
            ],
        ]
    ])
    @endcomponent
@endsection

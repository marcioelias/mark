@extends('layouts.crud.edit', [
    'title' => 'Alterar Regra',
    'route' => route('tag_rule.update', $tagRule->id),
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
                'inputSize' => 5,
                'indexSelected' => $tagRule->product_id
            ],
            [
                'type' => 'select',
                'field' => 'lead_status_id',
                'label' => 'Status do Lead',
                'items' => $leadStatuses,
                'displayField' => 'status',
                'keyField' => 'id',
                'inputSize' => 5,
                'indexSelected' => $tagRule->lead_status_id
            ],
            [
                'type' => 'checkbox',
                'field' => 'active',
                'label' => 'Ativo',
                'checked' => true,
                'required' => true,
                'inputSize' => 2,
                'checked' => $tagRule->active
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
                'inputSize' => 5,
                'indexSelected' => $tagRule->tag_id
            ],
        ]
    ])
    @endcomponent
@endsection

@extends('layouts.crud.create', [
    'title' => 'Nova tag',
    'route' => route('tag.store'),
    'redirect' => route('tag.index')
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
                'inputSize' => 6
            ],
            [
                'type' => 'text',
                'field' => 'tag_name',
                'label' => 'Tag',
                'required' => true,
                'inputSize' => 6
            ]
        ]
    ])
    @endcomponent
@endsection

@extends('layouts.crud.create', [
    'title' => 'Nova Instância Whatsapp',
    'route' => route('whatsapp_instance.store'),
    'redirect' => route('whatsapp_instance.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'description',
                'label' => 'Descrição',
                'required' => true,
                'inputSize' => 6
            ],
            [
                'type' => 'select',
                'field' => 'product_id',
                'label' => 'Produto',
                'items' => $products,
                'displayField' => 'product_name',
                'keyField' => 'id',
                'inputSize' => 6
            ]
        ]
    ])
    @endcomponent
@endsection

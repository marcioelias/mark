@extends('layouts.crud.edit', [
    'title' => 'Alterar Instância Whatsapp',
    'route' => route('whatsapp_instance.update', $whatsappInstance->id),
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
            'inputSize' => 5,
            'inputValue' => $whatsappInstance->description
        ],
        [
            'type' => 'select',
            'field' => 'product_id',
            'label' => 'Produto',
            'items' => $products,
            'displayField' => 'product_name',
            'keyField' => 'id',
            'inputSize' => 5,
            'indexSelected' => $whatsappInstance->product_id
        ],
        [
            'type' => 'checkbox',
            'field' => 'active',
            'label' => 'Ativo',
            'inputSize' => 2,
            'checked' => ($whatsappInstance->whatsapp_instance_status_id != $wppInstStatuses::DISABLED),
            'disabled' => ($whatsappInstance->whatsapp_instance_status_id == $wppInstStatuses::PENDING),
        ]
    ]
])
@endcomponent
@endsection

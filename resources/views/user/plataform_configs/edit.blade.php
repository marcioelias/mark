@extends('layouts.crud.edit', [
    'title' => 'Alterar Configuração',
    'route' => route('plataform_config.update', $plataformConfig->id),
    'redirect' => route('plataform_config.index')
])

@section('create-form')
@component('components.form-group', [
    'inputs' => [
        [
            'type' => 'select',
            'field' => 'plataform_id',
            'label' => 'Plataforma',
            'items' => $plataforms,
            'displayField' => 'plataform_name',
            'keyField' => 'id',
            'inputSize' => 3,
            'readOnly' => true,
            'indexSelected' => $plataformConfig->plataform_id
        ],
        [
            'type' => 'text',
            'field' => 'plataform_key',
            'label' => 'Chave',
            'required' => true,
            'inputSize' => 7,
            'inputValue' => $plataformConfig->plataform_key
        ],
        [
            'type' => 'checkbox',
            'field' => 'active',
            'label' => 'Ativo',
            'checked' => true,
            'required' => true,
            'inputSize' => 2,
            'checked' => $plataformConfig->active
        ]
    ]
])
@endcomponent
@endsection

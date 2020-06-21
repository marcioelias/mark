@extends('layouts.crud.create', [
    'title' => 'Nova Configuração',
    'route' => route('plataform_config.store'),
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
                'inputSize' => 3
            ],
            [
                'type' => 'text',
                'field' => 'plataform_key',
                'label' => 'Chave',
                'required' => true,
                'inputSize' => 7
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
@endsection

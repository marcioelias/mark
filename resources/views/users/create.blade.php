@extends('layouts.crud.create', [
    'title' => 'Novo Cliente',
    'route' => route('user.store'),
    'redirect' => route('user.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'name',
                'label' => 'Nome',
                'required' => true,
                'inputSize' => 6
            ],
            [
                'type' => 'text',
                'field' => 'customer_code',
                'label' => 'Código do cliente',
                'required' => true,
                'inputSize' => 4
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
                'type' => 'text',
                'field' => 'email',
                'label' => 'E-mail',
                'required' => true,
                'inputSize' => 6,
                'icon' => [
                    'side' => 'left',
                    'type' => 'mail',
                    'divider' => true
                ]
            ],
            [
                'type' => 'text',
                'field' => 'phone_number',
                'label' => 'Telefone',
                'required' => true,
                'inputSize' => 6,
                'css' => 'phone_with_ddd',
                'icon' => [
                    'side' => 'left',
                    'type' => 'phone',
                    'divider' => true
                ]
            ],
        ]
    ])
    @endcomponent
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'select',
                'field' => 'plan_id',
                'label' => 'Plano',
                'items' => $plans,
                'displayField' => 'plan_name',
                'keyField' => 'id',
                'inputSize' => 4
            ],
            [
                'type' => 'password',
                'field' => 'password',
                'label' => 'Senha',
                'required' => true,
                'inputSize' => 4
            ],
            [
                'type' => 'password',
                'field' => 'password_confirmation',
                'label' => 'Confirmação',
                'required' => true,
                'inputSize' => 4
            ]
        ]
    ])
    @endcomponent
@endsection

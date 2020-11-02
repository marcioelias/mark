@extends('layouts.crud.edit', [
    'title' => 'Alterar Cliente',
    'route' => route('user.update', $user->id),
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
                'inputSize' => 6,
                'inputValue' => $user->name
            ],
            [
                'type' => 'text',
                'field' => 'customer_code',
                'label' => 'Código do cliente',
                'required' => true,
                'inputSize' => 4,
                'inputValue' => $user->customer_code
            ],
            [
                'type' => 'checkbox',
                'field' => 'active',
                'label' => 'Ativo',
                'checked' => true,
                'required' => true,
                'inputSize' => 2,
                'checked' => $user->active
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
                ],
                'readOnly' => true,
                'inputValue' => $user->email
            ],
            [
                'type' => 'text',
                'field' => 'phone_number',
                'label' => 'Telefone',
                'required' => true,
                'inputSize' => 6,
                'icon' => [
                    'side' => 'left',
                    'type' => 'phone',
                    'divider' => true
                ],
                'inputValue' => $user->phone_number
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
                'inputSize' => 4,
                'indexSelected' => $user->plan_id
            ]/* ,
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
            ] */
        ]
    ])
    @endcomponent
@endsection

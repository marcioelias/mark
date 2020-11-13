@extends('layouts.crud.edit', [
    'title' => 'Minha Conta',
    'route' => route('admin.profile.update', $admin->id),
    'redirect' => route('admin.index')
])

@section('create-form')
    <div class="card mb-0">
        <div class="card-header px-0">
            <div class="card-title w-100 border-bottom-light">Minha Conta</div>
        </div>
        <div class="card-body px-0 pb-0">
            @component('components.form-group', [
                'inputs' => [
                    [
                        'type' => 'text',
                        'field' => 'name',
                        'label' => 'Nome',
                        'required' => true,
                        'inputSize' => 4,
                        'inputValue' => $admin->name
                    ],
                    [
                        'type' => 'text',
                        'field' => 'email',
                        'label' => 'E-mail',
                        'inputSize' => 4,
                        'inputValue' => $admin->email
                    ],
                    [
                        'type' => 'text',
                        'field' => 'username',
                        'label' => 'Usuário',
                        'inputSize' => 4,
                        'inputValue' => $admin->username
                    ]
                ]
            ])
            @endcomponent
        </div>
    </div>
    <div class="card mb-0">
        <div class="card-header px-0">
            <div class="card-title d-flex align-items-baseline w-100 border-bottom-light">
                Alteração de Senha
                @component('components.input-checkbox', [
                    'type' => 'checkbox',
                    'field' => 'change_password',
                    'label' => '',
                    'css' => 'margin-bottom: 0px !important;',
                    'inputSize' => 4,
                    'checked' => false
                ])
                @endcomponent
            </div>
        </div>
        <div class="card-body px-0 pb-0">
            @component('components.form-group', [
                'inputs' => [
                    [
                        'type' => 'password',
                        'field' => 'current_password',
                        'label' => 'Senha Atual',
                        'inputSize' => 4,
                    ],
                    [
                        'type' => 'password',
                        'field' => 'password',
                        'label' => 'Nova senha',
                        'inputSize' => 4,
                    ],
                    [
                        'type' => 'password',
                        'field' => 'password_confirmation',
                        'label' => 'Confirmação',
                        'inputSize' => 4,
                    ],
                ]
            ])
            @endcomponent
        </div>
    </div>
@endsection

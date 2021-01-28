@extends('layouts.crud.edit', [
    'title' => 'Minha Conta',
    'route' => route('user.profile.update', $user->id),
    'redirect' => route('index')
])

@section('create-form')
    @if($errors->has('msg'))
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Atenção</h4>
        <p class="mb-0">
            {{$errors->first('msg')}}
        </p>
    </div>
    @endif
    <div class="card mb-0">
        <div class="card-header px-0">
            <div class="card-title w-100 border-bottom-light">Dados Pessoais / Contato</div>
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
                        'inputValue' => $user->name
                    ],
                    [
                        'type' => 'text',
                        'field' => 'last_name',
                        'label' => 'Sobrenome',
                        'inputSize' => 4,
                        'inputValue' => $user->last_name
                    ],
                    [
                        'type' => 'text',
                        'field' => 'doc_number',
                        'label' => 'CPF',
                        'css' => 'cpf',
                        'inputSize' => 4,
                        'inputValue' => $user->doc_number
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
                        'css' => 'phone_with_ddd',
                        'icon' => [
                            'side' => 'left',
                            'type' => 'phone',
                            'divider' => true
                        ],
                        'inputValue' => $user->phone_area ?? '' . $user->phone_number
                    ],
                ]
            ])
            @endcomponent
        </div>
    </div>
    <div class="card mb-0">
        <div class="card-header px-0">
            <div class="card-title w-100 border-bottom-light">Endereço</div>
        </div>
        <div class="card-body px-0 pb-0">
            @component('components.form-group', [
                'inputs' => [
                    [
                        'type' => 'text',
                        'field' => 'zip_code',
                        'label' => 'CEP',
                        'css' => 'cep',
                        'inputSize' => 3,
                        'icon' => [
                            'side' => 'left',
                            'type' => 'map-pin',
                            'divider' => true
                        ],
                        'inputValue' => $user->zip_code
                    ],
                    [
                        'type' => 'text',
                        'field' => 'street_name',
                        'label' => 'Endereço/Logradouro',
                        'inputSize' => 7,
                        'inputValue' => $user->street_name
                    ],
                    [
                        'type' => 'text',
                        'field' => 'street_number',
                        'label' => 'Número',
                        'inputSize' => 2,
                        'inputValue' => $user->street_number
                    ],
                ]
            ])
            @endcomponent
            @component('components.form-group', [
                'inputs' => [
                    [
                        'type' => 'text',
                        'field' => 'neighborhood',
                        'label' => 'Bairro',
                        'inputSize' => 5,
                        'inputValue' => $user->neighborhood
                    ],
                    [
                        'type' => 'text',
                        'field' => 'city',
                        'label' => 'Cidade',
                        'inputSize' => 5,
                        'inputValue' => $user->city
                    ],
                    [
                        'type' => 'select',
                        'field' => 'state',
                        'label' => 'Estado',
                        'inputSize' => 2,
                        'items' => [
                            '' => '',
                            'AC' => 'AC',
                            'AL' => 'AL',
                            'AM' => 'AM',
                            'AP' => 'AP',
                            'BA' => 'BA',
                            'CE' => 'CE',
                            'DF' => 'DF',
                            'ES' => 'ES',
                            'GO' => 'GO',
                            'MA' => 'MA',
                            'MT' => 'MT',
                            'MS' => 'MS',
                            'MG' => 'MG',
                            'PA' => 'PA',
                            'PB' => 'PB',
                            'PR' => 'PR',
                            'PE' => 'PE',
                            'PI' => 'PI',
                            'RJ' => 'RJ',
                            'RN' => 'RN',
                            'RO' => 'RO',
                            'RS' => 'RS',
                            'RR' => 'RR',
                            'SC' => 'SC',
                            'SE' => 'SE',
                            'SP' => 'SP',
                            'TO' => 'TO'
                        ],
                        'indexSelected' => $user->state
                    ],
                ]
            ])
            @endcomponent
        </div>
    </div>
    <div class="card mb-0">
        <div class="card-header px-0">
            <div class="card-title d-flex align-items-baseline w-100 border-bottom-light">
                Alterar senha
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

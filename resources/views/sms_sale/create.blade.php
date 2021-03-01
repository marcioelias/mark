@extends('layouts.crud.create', [
    'title' => 'Nova Venda de 0Pacote',
    'route' => route('sms_sale.store'),
    'redirect' => route('sms_sale.index')
])


@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'select',
                'field' => 'user_id',
                'label' => 'Cliente',
                'items' => $users,
                'displayField' => 'name',
                'keyField' => 'id',
                'inputSize' => 6,
                'defaultNone' => true
            ],
            [
                'type' => 'select',
                'field' => 'sms_package_id',
                'label' => 'Pacote de SMS',
                'items' => $smsPackages,
                'displayField' => 'sms_package_name',
                'keyField' => 'id',
                'inputSize' => 6,
                'defaultNone' => true
            ]
        ]
    ])
    @endcomponent
@endsection

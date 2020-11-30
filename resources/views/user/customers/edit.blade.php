@extends('layouts.crud.edit', [
    'title' => 'Alterar Cliente',
    'route' => route('customer.update', $customer->id),
    'redirect' => route('customer.index')
])

@section('create-form')
@component('components.form-group', [
    'inputs' => [
        [
            'type' => 'text',
            'field' => 'customer_name',
            'label' => 'Nome',
            'required' => true,
            'inputSize' => 8,
            'inputValue' => $customer->customer_name
        ],
        [
            'type' => 'select',
            'field' => 'customer_status_id',
            'label' => 'Status',
            'items' => $customerStatuses,
            'displayField' => 'customer_status',
            'keyField' => 'id',
            'inputSize' => 4,
            'defaultNone' => true,
            'indexSelected' => $customer->customer_status_id
        ]
    ]
])
@endcomponent
@component('components.form-group', [
    'inputs' => [
        [
            'type' => 'text',
            'field' => 'customer_phone_number',
            'label' => 'Telefone',
            'required' => true,
            'inputSize' => 6,
            'icon' => [
                'side' => 'left',
                'type' => 'phone',
                'divider' => true
            ],
            'inputValue' => $customer->customer_phone_number
        ],
        [
            'type' => 'text',
            'field' => 'customer_email',
            'label' => 'E-mail',
            'required' => true,
            'inputSize' => 6,
            'icon' => [
                'side' => 'left',
                'type' => 'at-sign',
                'divider' => true
            ],
            'inputValue' => $customer->customer_email
        ],
    ]
])
@endcomponent
@endsection

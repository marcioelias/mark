@extends('layouts.crud.edit', [
    'title' => 'Alterar Status de Cliente',
    'route' => route('customer_status.update', $customerStatus->id),
    'redirect' => route('customer_status.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'customer_status',
                'label' => 'Status',
                'required' => true,
                'inputSize' => 12,
                'inputValue' => $customerStatus->customer_status
            ]
        ]
    ])
    @endcomponent
@endsection

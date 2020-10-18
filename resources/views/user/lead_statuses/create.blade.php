@extends('layouts.crud.create', [
    'title' => 'Novo Status',
    'route' => route('lead_status.store'),
    'redirect' => route('lead_status.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'status',
                'label' => 'Status',
                'required' => true,
                'inputSize' => 12
            ]
        ]
    ])
    @endcomponent
@endsection

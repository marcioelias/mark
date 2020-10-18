@extends('layouts.crud.edit', [
    'title' => 'Alterar Status',
    'route' => route('lead_status.update', $leadStatus->id),
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
                'inputSize' => 12,
                'inputValue' => $leadStatus->status
            ]
        ]
    ])
    @endcomponent
@endsection

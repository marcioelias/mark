@extends('layouts.crud.create', [
    'title' => 'Nova tag',
    'route' => route('tag.store'),
    'redirect' => route('tag.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'tag_name',
                'label' => 'Tag',
                'required' => true,
                'inputSize' => 12
            ]
        ]
    ])
    @endcomponent
@endsection

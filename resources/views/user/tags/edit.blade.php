@extends('layouts.crud.edit', [
    'title' => 'Alterar Tag',
    'route' => route('tag.update', $tag->id),
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
                'inputSize' => 12,
                'inputValue' => $tag->tag_name
            ]
        ]
    ])
    @endcomponent
@endsection

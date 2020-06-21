@extends('layouts.crud.vue', [
    'title' => 'Novo Funil',
    'route' => route('tag.store'),
    'redirect' => route('tag.index')
])

@section('vue-component')
    <div id="funnel">
        <funnel-component />
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/user/funnel.js') }}"></script>
@endsection

@extends('layouts.contentLayoutMaster')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-body">
        @yield('vue-component')
    </div>
</div>
@endsection

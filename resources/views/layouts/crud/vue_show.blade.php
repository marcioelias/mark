@extends('layouts.contentLayoutMaster')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-body pb-0">
        @yield('show-content')
    </div>
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ $route }}" class="btn btn-primary">
            <i class="fa fa-times"></i> Fechar Visualização
        </a>
    </div>
</div>
@endsection

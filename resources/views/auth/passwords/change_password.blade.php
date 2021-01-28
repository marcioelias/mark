@extends('layouts/fullLayoutMaster')

@section('title', 'Alterar senha')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/pages/authentication.css')) }}">
@endsection
@section('content')
<section class="row flexbox-container">
  <div class="col-xl-7 col-md-9 col-10 d-flex justify-content-center px-0">
    <div class="card bg-authentication rounded-0 mb-0">
      <div class="row m-0">
        <div class="col-lg-6 d-lg-block d-none text-center align-self-center">
          <img src="{{ asset('images/pages/forgot-password.png') }}" alt="branding logo">
        </div>
        <div class="col-lg-6 col-12 p-0">
          <div class="card rounded-0 mb-0 px-2 py-1">
            <div class="card-header pb-1">
              <div class="card-title">
                <h4 class="mb-0">Alterar senha</h4>
              </div>
            </div>
            <p class="px-2 mb-0">Por favor escolha uma senha pessoal para uso como ConvertAll.</p>
            <div class="card-content">
              <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.change') }}">
                  @csrf
                  <div class="form-label-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                      name="password" value="{{ old('password') }}" placeholder="password" required autocomplete="password"
                      autofocus>

                    <label for="password">Senha</label>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>

                  <div class="form-label-group">
                    <input id="password_confirmation" type="password" class="form-control @error('password') is-invalid @enderror"
                      name="password_confirmation" value="{{ old('password') }}" placeholder="password" required autocomplete="password"
                      autofocus>

                    <label for="password">Confirmação de Senha</label>

                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>

                  {{-- <div class="float-md-left d-block mb-1">
                    <a href="{{ route('admin.login') }}" class="btn btn-outline-primary btn-block px-75">Voltar ao Login</a>
                  </div> --}}
                  <div class="float-md-right d-block mb-1">
                    <button type="submit" class="btn btn-primary btn-block px-75">Continuar</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection

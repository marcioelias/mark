@extends('layouts/fullLayoutMaster')

@section('title', 'Login Administrativo')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/pages/authentication.css')) }}">
@endsection

@section('content')
<section class="row flexbox-container">
  <div class="col-xl-8 col-11 d-flex justify-content-center">
    <div class="card bg-authentication rounded-0 mb-0">
      <div class="row m-0">
        <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
          <img src="{{ asset('images/pages/login.png') }}" alt="branding logo">
        </div>
        <div class="col-lg-6 col-12 p-0">
          <div class="card rounded-0 mb-0 px-2">
            <div class="card-header pb-1">
              <div class="card-title">
                <h4 class="mb-0">Login Administrativo</h4>
              </div>
            </div>
            <p class="px-2">Informe seus dados para continuar.</p>
            <div class="card-content mb-2">
              <div class="card-body pt-1">
                <form method="POST" action="{{ route('admin.login') }}">
                  @csrf
                  <fieldset class="form-label-group form-group position-relative has-icon-left">

                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                      name="username" placeholder="Usuário" value="{{ old('username') }}" required autocomplete="username"
                      autofocus>

                    <div class="form-control-position">
                      <i class="feather icon-user"></i>
                    </div>
                    <label for="username">Usuário</label>
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </fieldset>

                  <fieldset class="form-label-group position-relative has-icon-left">

                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                      name="password" placeholder="Senha" required autocomplete="current-password">

                    <div class="form-control-position">
                      <i class="feather icon-lock"></i>
                    </div>
                    <label for="password">Senha</label>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </fieldset>
                  <div class="form-group d-flex justify-content-between align-items-center">
                    <div class="text-left">
                      <fieldset class="checkbox">
                        <div class="vs-checkbox-con vs-checkbox-primary">
                          <input type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                          <span class="vs-checkbox">
                            <span class="vs-checkbox--check">
                              <i class="vs-icon feather icon-check"></i>
                            </span>
                          </span>
                          <span class="">Continuar conectado</span>
                        </div>
                      </fieldset>
                    </div>
                    @if (Route::has('password.request'))
                    <div class="text-right"><a class="card-link" href="{{ route('admin.password.request') }}">
                        Esqueceu a senha?
                      </a></div>
                    @endif

                  </div>
                  <button type="submit" class="btn btn-primary float-right btn-inline">Entrar</button>
                </form>
              </div>
            </div>
            {{-- <div class="login-footer">
              <div class="divider">
                <div class="divider-text">OR</div>
              </div>
              <div class="footer-btn d-inline">
                <a href="#" class="btn btn-facebook"><span class="fa fa-facebook"></span></a>
                <a href="#" class="btn btn-twitter white"><span class="fa fa-twitter"></span></a>
                <a href="#" class="btn btn-google"><span class="fa fa-google"></span></a>
                <a href="#" class="btn btn-github"><span class="fa fa-github-alt"></span></a>
              </div>
            </div> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

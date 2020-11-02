@extends('layouts.fullLayoutMaster')

@section('title', 'Novo cliente')

@section('page-style')
{{-- Page Css files --}}
{{-- <link rel="stylesheet" href="{{ asset(mix('css/pages/authentication.css')) }}"> --}}
@endsection

@section('content')
<section class="row flexbox-container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="card-title">
                <p>Olá, FULANO! Seja bem vindo ao Mark.</p>
            </div>
        </div>
        <div class="card-body">
            <p>
                Agora você pode gerenciar todas as suas notificações de vendas em um único lugar.
            </p>
            <p>
                Para ter acesso ao sistema utilize os seguites dados:
                <ul>
                    <li><strong>Login:</strong> email@provedor.com</li>
                    <li><strong>Senha:</strong> HE&DH*D</li>
                </ul>
            </p>
            <hr>
            <p>
                Sua senha deverá ser alterada no primeiro acesso, para tanto você será redirecionado após seu primeiro acesso a uma página que possibilitará a escolha de uma senha a seu gosto.
            </p>
            <p>
                É importante ressaltar que sua senha é pessoal, por tanto não repasse esses dados de acesso a terceiros. A Equipe da Mark NUNCA solicita seus dados de acesso!
            </p>
            <p>
                Clique no botão abaixo para acessar sua conta!
            </p>
            <div class="d-flex flex-column justify-content-between align-items-center">
                <a href="#" class="btn btn-success">Acessar minha conta</a>
            </div>
        </div>
        <div class="card-footer">
            <span>2020 todos os direitos reservados</span>
        </div>
    </div>
</section>
@endsection

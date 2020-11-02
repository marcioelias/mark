<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Titulo' }}</title>

    @include('panels.styles')
</head>
<body>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="card-title">
                    <div class="d-flex align-items-end mb-2">
                        <img src="{{ asset('images/logo/logo-success.png') }}" alt="" srcset="" class="mr-2">
                        <p>
                            Olá, <strong>teste</strong>! Seja bem vindo ao Mark.
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p>
                    Agora você pode gerenciar todas as suas notificações de vendas em um único lugar.
                </p>
                <p>
                    Para ter acesso ao sistema utilize os seguites dados:
                    <ul>
                        <li><strong>Login:</strong> teste</li>
                        <li><strong>Senha:</strong> teste</li>
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
                    <a href="{{ env('APP_URL') }}" class="btn btn-lg btn-success">Acessar minha conta <i class="feather icon-arrow-right"></i></a>
                </div>
            </div>
            <div class="card-footer">
                <span>2020 todos os direitos reservados</span>
            </div>
        </div>
    </div>
</body>
</html>

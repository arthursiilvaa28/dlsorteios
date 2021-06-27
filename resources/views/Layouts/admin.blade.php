<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('Imagens/favicon.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('Css/admin-layout.css')}}">
    <link rel="stylesheet" href="@yield('css')">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous">
    </script>
    <title>DL Sorteios</title>
</head>
<body>
{{--<img  src="{{ asset('Imagens/favicon.png')}}" alt="logo">--}}
    <aside class="aside">
        <div class="cont-itens">

            <div>
               
                <ul class="itens">
                    <li><img class="favico-index-logo"  src="{{ asset('Imagens/favicon.png')}}" alt="logo"></li>
                    
                </ul>
            </div>
            <hr>
            <div>

                <ul class="itens">
                <li><a href="{{ route('user/index') }}" id="new_sorteio-id" class="iten">Visualizar Site</a></li> 
                </ul>
            </div>
            <hr>
           
            <div>
                <span class="title">SORTEIO</span>
                <ul class="itens">
                <li><a href="{{ route('admin/sorteio/new-sorteio') }}" id="new_sorteio-id" class="iten">Novo Sorteio</a></li>
                    <li><a href="{{ route('admin/sorteio/index') }}" class="iten" id="listar-id">Listar Sorteios</a></li>
                    
                </ul>
            </div>
            <hr>
            <div>
                <span class="title">VENDAS</span>
                <ul class="itens">
                    <li><a href="{{ route('admin/venda/pendente') }}" id="pendente-link" class="iten">Pendentes</a></li>
                    <li><a href="{{ route('admin/venda/analise') }}" id="analise-link" class="iten">Análises</a></li>
                    <li><a href="{{ route('admin/venda/completa') }}" id="completa-link" class="iten">Historico de vendas</a></li>
                </ul>
            </div>
            <hr>
            <div>
                <span class="title">Conta</span>
                <ul class="itens">
                    <form method="POST" action="{{ route('account/logout') }}" class="logout">
                        @csrf
                        <button class="iten">Sair</button>
                    </form>
                </ul>
            </div>
            <hr>
        </div>
    </aside>
    <header class="conteiner-header">
        <div class="content-header">
        
            
        </div>
    </header>
    <div class="conteiner">
        @yield('content')
    </div>
</body>
</html>
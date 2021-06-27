<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DL Sorteios</title>
    <link rel="shortcut icon" href="{{asset('Imagens/favicon.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('Css/app.css')}}">
    <link rel="stylesheet" href="@yield('css')">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous">
    </script>
</head>
<body>
    <div class="menu-resposivo-conteiner">
        <div class="menu-resposivo">
        <ul class="navegations-responsivo"> 
            <li>
                <a href="{{ route('user/index') }}" class="items-res">
                      <i class="fas fa-home"></i>
                    <span class="items-text-res">INÍCIO</span>
                </a>
            </li>
           
            <li>                   
                <a href="{{ route('user/meus-bilhetes') }}" class="items-res">
                     <i class="fas fa-ticket-alt"></i>
                    <span class="items-text-res">MEUS BILHETES</span>
                </a>
            </li>
            <li>                   
                <a href="{{ route('user/contatos') }}" class="items-res">
                <i class="fas fa-headset"></i>
                    <span class="items-text-res">SUPORTE</span>
                </a>
            </li>
            @if(Auth::check())
            <li>
                <a href="{{ route('user/profile') }}" class="items-res">
                <i id="logo-ico" class="fas fa-user"></i>
                    <span class="items-text-res">PERFIL</span>
                </a>
            </li>
            @endif
            @if(!Auth::check())
                <li>                   
                    <a href="{{ route('account/login') }}" class="items-res">
                        <span class="items-text-res">LOGIN</span>
                    </a>
                </li>
            @endif
        </ul>
        @if(Auth::check())
             
            <div class="perfil-res">
                    <form method="POST" action="{{ route('account/logout') }}" class="logout-res">
                        @csrf
                        <button class="text-profilehoverEx">Sair</button>
                    </form>
             
            </div>
        @endif
        </div>
    </div>
    <header id="header_conteiner">
        <nav class="header_content">
            <div class="logo"><a href="{{ route('user/index') }}">DLSORTEIOS</a> </div>
            <i class="fas fa-bars" id="humbugue" onclick="menuShow()"></i>
            
            <div class="nav-perfil">
                <ul class="navegations"> 
                    <li>
                        <a href="{{ route('user/index') }}" class="items">
                        <i class="fas fa-home"></i>
                            <span class="items-text">INÍCIO</span>
                        </a>
                    </li>
                   
                    <li>                   
                        <a href="{{ route('user/meus-bilhetes') }}" class="items">
                        <i class="fas fa-ticket-alt"></i>
                            <span class="items-text">MEUS BILHETES</span>
                        </a>
                    </li>
                    <li>                   
                        <a href="{{ route('user/contatos') }}" class="items">
                              <i class="fas fa-headset"></i>
                            <span class="items-text">SUPORTE</span>
                        </a>
                    </li>
                    @if(!Auth::check())
                        <li>                   
                            <a href="{{ route('account/login') }}" class="items">
                                <span class="items-text">LOGIN</span>
                            </a>
                        </li>
                    @endif
                </ul>
                @if(Auth::check())
                <script>
             const iduser = '{{ Auth::user()->id }}';
             const funcao_adm = 'Painel de controle'
            function show(){$('.show').toggle();
            if(iduser == 1){document.getElementById('user-adm-visu').innerHTML = funcao_adm;return false;}}
                </script>
                    <div class="perfil">
                        <div onclick="show()">
                        <i id="logo-ico" class="fas fa-user"></i>
                        <i class="fas fa-chevron-down"></i>
                           {{-- <span class="items-text " >{{ Auth::user()->name }}</span> --}}
                           {{-- <img src="{{ asset('Imagens/showProfile.png') }}" alt="show"  class="items-img-show"> --}}
                        </div>
                        <div class="show">
                            <div class="profile-header">Gerenciar conta</div>
                            <hr>
                            <a id="user-adm-visu" href="{{ route('admin/sorteio/index') }}" class="text-profilehover"></a>
                            <p></p>
                            <a href="{{ route('user/profile') }}" class="text-profilehover">Perfil</a>
                            <form method="POST" action="{{ route('account/logout') }}" class="logout">
                                @csrf
                                <button class="text-profilehover">Sair</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </nav>
    </header>
    <main class="conteiner site">
         <div class="content">
            {{-- conteudo que vai variar --}}
            @yield('content')
            <section class="help">
                <header class="help-header">
                    <h1>VEJA COMO É RÁPIDO E FÁCIL PARTICIPAR</h1>
                    <hr>
                </header>
                <ul class="conteiner-help">
                    <li class="content-help">
                        <img src="{{ asset('Imagens/passo1.png') }}" alt="Passo 1">
                        <div>
                            <h1>Escolha um sorteio:</h1>
                            <h2>Muito fácil participar, comesse escolhendo um sorteio ativo.</h2>
                        </div>
                    </li>
                    <li class="content-help">
                        <img src="{{ asset('Imagens/passo2.png') }}" alt="Passo 2">
                        <div>
                            <h1>Selecione os números:</h1>
                            <h2>Escolha quantos quizer, quanto mais, mais chances de ganhar !</h2>
                        </div>
                    </li>
                    <li class="content-help">
                        <img src="{{ asset('Imagens/passo3.png') }}" alt="Passo 3">
                        <div>
                            <h1>Faça o pagamento:</h1>
                            <h2>Escolha um das formas de pagamentos disponíveis.</h2>
                        </div>
                    </li>
                    <li class="content-help">
                        <img src="{{ asset('Imagens/passo4.png') }}" alt="Passo 4">
                        <div>
                            <h1>Aguarde o sorteio:</h1>
                            <h2>Agurde, espere até a data para visualizar o resultado.</h2>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </main>
    @yield('calculator')
    @yield('confirmation')
    @yield('resultPayCota')
    <footer class="conteiner footer-color">
        <div class="content footer-content">
            <section class="footer">
                <div>Copywrite © 2021 - Todos direitos reservados - DLSORTEIO</div>
                <div>Desenvolvido: Gabriel Martins e Artur Silva</div>
            </section>
        </div>
    </footer>
    @yield('backgroudAch')
    <script>
       
        function menuShow(){
            $('.menu-resposivo-conteiner').toggle();
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>




@extends('Layouts/site')
@section('css', asset('Css/login.css'))
@section('content')

    <div class="center-lg-rg">

    <div class="conteiner-login">
    
        <form method="POST" action="{{ route('account/login/do') }}" class="form">

        <div class="favicon-ico-logo-login">
        <img  src="{{ asset('Imagens/favicon.png')}}" alt="logo">
        </div>

        @csrf
            <div>
                @error('error')
                    <div id="error">{{ $message }}</div>
                @enderror
                <div class="inputs">
                    <div class="input">
                        <span>Email</span>
                        <div class="inp">
                            <input type="email" id="email" name="email" placeholder="Digite seu email" required value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="input">
                        <span>Senha</span>
                        <div class="inp">
                            <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                            <img src="{{ asset('Imagens/eye-no.png') }}" alt="Olho" onclick="showPass()" id="eye">
                        </div>
                    </div>
                </div>
                <button class="button">ENTRAR</button>
                <div class="register-forgot">
                    <a href="{{ route('account/register') }}">Cadastre-se</a>
                    {{-- <a href="">Esqueceu a senha?</a> --}}
                </div>
            </div>
        </form>
</div>
</div>
    <script>
        var eyeValue = false;
        function showPass() {
            const password = document.querySelector('#password');
            const eye = document.querySelector('#eye');
            if(eyeValue){
                password.setAttribute("type", "password");
                eye.setAttribute("src", "{{ asset('Imagens/eye-no.png') }}");
            }else{
                password.setAttribute("type", "text");
                eye.setAttribute("src", "{{ asset('Imagens/eye.png') }}");
            }
            eyeValue=!eyeValue;   
        }
    </script>


    
@endsection
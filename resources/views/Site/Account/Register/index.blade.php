@extends('Layouts/site')
@section('css', asset('Css/login.css'))
@section('content')
<div class="center-lg-rg">
    <div id="cd-conteiner" class="conteiner-login">
        <form method="POST" action="{{ route('account/register/do') }}" class="form">

        <div class="favicon-ico-logo-login">
        <img  src="{{ asset('Imagens/favicon.png')}}" alt="logo">
        </div>

            @csrf
            <div>
                <div class="inputs">
                    <div class="input">
                        <span>Nome completo</span>
                        <div class="inp">
                            <input type="text" name="name" placeholder="Maria Santos" required  value="{{ old('name') }}">
                        </div>
                        @error('name')
                            <div class="errors">{{ $message }}</div>
                        @enderror        
                    </div>
                    <div class="input">
                        <span>Email</span>
                        <div class="inp">
                            <input type="text" name="email" placeholder="example@gmail.com" required  value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <div class="errors">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input">
                        <span>Telefone</span>
                        <div class="inp">
                            <input type="text" name="phone" placeholder="85991313501" required value="{{ old('phone') }}" >
                        </div>
                        @error('phone')
                            <div class="errors">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input">
                        <span>Senha</span>
                        <div class="inp">
                            <input type="password" name="password" id="password0" placeholder="example123" required value="{{ old('password') }}">
                            <img src="{{ asset('Imagens/eye-no.png') }}" alt="Olho" onclick="showPass(0)" id="eye0">
                        </div>
                        @error('password')
                            <div class="errors">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input">
                        <span>Confirme senha</span>
                        <div class="inp">
                            <input type="password" name="password_confirmation" id="password1" placeholder="example123" required value="{{ old('password_confirmation') }}">
                            <img src="{{ asset('Imagens/eye-no.png') }}" alt="Olho" onclick="showPass(1)" id="eye1">
                        </div>
                        @error('password_confirmation')
                            <div class="errors">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="button">REGISTRE-ME</button>
            </div>
        </form>
    </div>
    </div>
    <script>
        var eyeValue = [false, false];
        function showPass(n) {
            const password = document.querySelector('#password'+n);
            const eye = document.querySelector('#eye'+n);
            if(eyeValue[n]){
                password.setAttribute("type", "password");
                eye.setAttribute("src", "{{ asset('Imagens/eye-no.png') }}");
            }else{
                password.setAttribute("type", "text");
                eye.setAttribute("src", "{{ asset('Imagens/eye.png') }}");
            }
            eyeValue[n]=!eyeValue[n];   
        }
    </script>
@endsection
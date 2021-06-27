@extends('Layouts/site')
@section('css', asset('Css/profile-index.css'))
@section('content')

    <div class="minhaconta-reponsive">
    <div class="profile-index">
    @error('dados')
            <script>
                alert('{{$message }}');
            </script>
    @enderror  
        <span class="mn-minha-conta">MINHA CONTA</span>
            
        <div class="email-user-perfil">

             <span class="span-user">NOME COMPLETO</span>
             <p>
            <div class="input-infor">
                <span class="input-infor-user">{{ Auth::user()->name }}</span>
            </div>

        </div>
        
        <div class="email-user-perfil">

             <span class="span-user">EMAIL</span>
             <p>
            <div class="input-infor">
                <span class="input-infor-user">{{ Auth::user()->email }}</span>
            </div>

        </div>

        
        <div class="email-user-perfil">

             <span class="span-user">TELEFONE</span>
             <p>
            <div class="input-infor">
                <span class="input-infor-user">{{ Auth::user()->phone }}</span>
            </div>

        </div>

        <form method="post" action="{{ route('user/nvsenha') }}" >
        @csrf
        <div class="email-user-perfil">

             <span class="span-user">SENHA ATUAL</span>
             <p>
             <input type="password" id="password" class="password" name="passwordAtual"  required>

        </div>
        
        <div class="email-user-perfil">

             <span class="span-user">NOVA SENHA</span>
             <p>
             <input type="password" id="password1" class="password" name="passwordNew">

        </div>
                    
         <input type="submit" onclick="capturar()" id="send" value="Atualizar" class="bnt-atualizar">

         </form>

    </div>
</div>

  

@endsection
@extends('Layouts/site')
@section('css', asset('Css/home.css'))
@section('content')
    @if(isset($sorteios))
        <div class="slide">
            <img src="{{ asset('storage/sorteios/'.$sorteios[0]->foto) }}" alt="Slide" id="slid-img">
            <div class="play">
                <img src="{{ asset('Imagens/anterior.png') }}" alt="Anterior" onclick="anterior()" id="anterior">
                <img src="{{ asset('Imagens/proximo.png') }}" alt="Próximo" onclick="proximo()" id="proximo">
            </div>
            <a href="{{ route('user/show-sorteio', ['id' => $sorteios[0]->id] ) }}" class="button-go" id="linkSlid">PARTICIPE AGORA !</a>
        </div>
        <div class="conteiner-now">
            <header class="header-now">
                <h1>SORTEIOS DISPONÍVEIS</h1>
                <hr>
            </header>
            <div class="now">
                @foreach ($sorteios as $sorteio)
                    <a href="{{ route('user/show-sorteio', ['id' => $sorteio->id] ) }}" class="box-now">
                    <span>{{ $sorteio->sorteio }}</span>
                        <img src="{{ asset('storage/sorteios/'.$sorteio->foto) }}" alt="Now">
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="sorteioNot">
            <h1>Não há sorteio no momento :(</h1>
            <h2>Volte em outro momento, estaremos a sua espera !</h2>
        </div>
    @endif
    <script>
        var slideAtual = 0;
        const url=location.origin;
        const slidImg = document.querySelector('#slid-img');
        const slidLink = document.querySelector('#linkSlid');
        const data= '<?php echo json_encode($sorteios) ?>';
        
        
        const sorteios= JSON.parse(data);
        verifyPlay();
        function anterior(){
            if(slideAtual > 0 ){
                slideAtual--;
                const img="/storage/sorteios/"+sorteios[slideAtual].foto;
                const slide=url+img;
                slidImg.setAttribute('src', slide);
                slidLink.setAttribute('href', url+'/sorteio/'+sorteios[slideAtual].id);
                verifyPlay();
            }
        }
        function proximo(){
            if(slideAtual < sorteios.length-1 ){
                slideAtual++;
                const img="/storage/sorteios/"+sorteios[slideAtual].foto;
                const slide=url+img;
                slidImg.setAttribute('src', slide);
                slidLink.setAttribute('href', url+'/sorteio/'+sorteios[slideAtual].id);
                verifyPlay();
            }
        }
        function verifyPlay() {
            if(slideAtual === 0 ){            
                $('#anterior').hide();
            }
            if(slideAtual > 0 ){
                $('#anterior').show();
            }
            if(slideAtual === sorteios.length-1 ){
                $('#proximo').hide();
            }
            if(slideAtual < sorteios.length-1 ){
                $('#proximo').show();
            }

        }   
    </script>
@endsection
@extends('Layouts/admin')
@section('css', asset('Css/new-sorteio.css'))
@section('content')
<style>
#new_sorteio-id{
    color: #fff;
}
</style>
<div class="conteiner-form-previu">
    <form  method="POST" action="{{ route('admin/sorteio/new-sorteio/do') }}" enctype="multipart/form-data" class="new-sorteio">
        @csrf
        <h1>NOVO SORTEIO</h1>
        
        @if($errors->all())
            @if ($errors->all()[0]=='sucess')
                <div class="sucess">{{$errors->all()[1]}}</div>   
            @else
                <div class="error">
                    {{$errors->all()[0]}}
                </div>
            @endif
        @endif
        
        <div class="conteiner-input">
            <div>Nome do sorteio</div>
            <input type="text" name="sorteio" id="name" placeholder="Nome do sorteio" value="{{ old('sorteio') }}" onkeyup="namePreviu()" required>
        </div>
        <div class="conteiner-input">
            <div>Selecione a imagem do sorteio</div>
            <input type="file" name="foto" id="imagem" value="{{ old('foto') }}" onchange="previuImg(event)" required>
        </div>
        <div class="conteiner-input">
            <div>Valor da cotas</div>
            <input type="number" name="valor" id="valor" placeholder="R$ 20" value="{{ old('valor') }}" onkeyup="valorPreviu()" required>
        </div>
        <div class="conteiner-input">
            <div>Número de cotas</div>
            <input type="number" name="numCotas" placeholder="300" value="{{ old('numCotas') }}" required>
        </div>
        <div class="conteiner-input">
            <div>Data do sorteio</div>
            <input type="text" name="data" id="data" value="{{ old('data') }}" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="12/13/2020" onkeyup="dataPreviu()" required>
        </div>
        <button>CADASTRAR SORTEIO</button>
    </form>
    <div class="previu">
        <img src="{{ asset('Imagens/example.png') }}" alt="img" id="previuImg">
        <div class="dados-sorteio">
            <div class="desc-sorteio" id="dataPreviu">Data do sorteio</div>
            <div class="title-sorteio" id="namePreviu">Nome do sorteio</div>
            <div class="desc-sorteio">Apartir de R$ <span id="valorPreviu">Valor do Sorteio</span></div>
        </div>
    </div>
</div>
<script>
    function previuImg(event) {
        var img = new Image(); 
        img.onload = function() { 
            const imgPa=1.78;
            const imgUpload=(this.width/this.height).toFixed(2);
            if(imgUpload==imgPa){
                const imgPreviu = document.getElementById('previuImg');
                imgPreviu.src = URL.createObjectURL(event.target.files[0]);
            }else{
                alert('Formato de imagem inválido !');
                $('#imagem').val("");
            }
        } 
        img.src = URL.createObjectURL(event.target.files[0]);

    }
    function dataPreviu() {
        const data=$('#data').val();
        $('#dataPreviu').html(data);
    }
    function namePreviu() {
        var name=$('#name').val();
        $('#namePreviu').html(name);
    }
    function valorPreviu() {
        var valor=$('#valor').val();
        $('#valorPreviu').html(valor);
    }
</script>
@endsection
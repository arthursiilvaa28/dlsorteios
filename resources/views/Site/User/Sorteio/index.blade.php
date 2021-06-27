@extends('Layouts/site')
@section('css', asset('Css/sorteio.css'))
@section('content')
    <div class="sorteio-img">
        <img src="{{ asset('storage/sorteios/'.$sorteio->foto) }}" alt="Slide" id="slid-img">
        <div class="dados-sorteio">
            <div class="desc-sorteio">Sorteio {{$sorteio->data}}</div>
            <div class="title-sorteio">{{$sorteio->sorteio}}</div>
            <div class="desc-sorteio">Apartir de R${{$sorteio->valor}}</div>
        </div>
    </div>
    <hr class="hr">
    <div class="conteiner-cotas">
        <header>
            <h2>COTAS:</h2>
            <div>Clique e selecione o quanto você quizer !</div>
        </header>
        <div class="cotas-filter">
            <span id="livres" onclick="livres('Disponivel')">Livres ({{$qtdCotas[0]}})</span>
            <span id="comprados" onclick="livres('Vendido')">Comprados ({{$qtdCotas[1]}})</span>
            <span id="reservados" onclick="livres('Reservado')">Reservados ({{$qtdCotas[2]}})</span>
        </div>
        <div class="cotas">
            @foreach ($cotas as $cota)    
                @switch($cota['status'])
                    @case('Vendido')
                        <div class="cota-x2 des-hover color-pay" id="{{'cota'.$cota['cota']}}">
                            {{$cota['cota']}}
                            <div class="descripition-cota">
                                <span>Comprador:</span>
                                <span>{{$cota['userName']}}</span>
                            </div>
                        </div>
                        @break
                    @case('Reservado')
                        <div class="cota-x2 des-hover color-reser" id="{{'cota'.$cota['cota']}}">
                            {{$cota['cota']}}
                            <div class="descripition-cota">
                                <span>Reservado por:</span>
                                <span>{{$cota['userName']}}</span>
                            </div>
                        </div>
                        @break
                    @case('Disponivel')
                        <div class="cota" id="{{'cota'.$cota['cota']}}" onclick="add({{$cota['cota']}})">{{$cota['cota']}}</div>
                        @break
                @endswitch
            @endforeach
        </div>
    </div>
@endsection
@section('calculator')
    <div class="conteiner" id="calculator">
        <form class="flex-pay" method="POST" action="{{ route('user/payment')}}" id="form-pay-cota">
            @csrf
            <div class="cotas-pay" id="cotas-pay-add">
                
            </div>
            <div class="pay">
                <div class="values-pay" id="values-pay-add">
                </div>
                <div class="payment" onclick="confimarCompra()">CONTINUAR</div>
            </div>
            <input type="hidden" name="sorteioId" value="{{ $sorteio->id }}">
        </form>

        <script>
            const sorteioId='<?=$sorteio->id?>';
            var cotasPay=[];
            const value='<?=$sorteio->valor?>';

            function confimarCompra(){
                const cotasMap = cotasPay.map((cotaAtual) => {
                    const cotaNw= cotaAtual+'  ';
                    return cotaNw;
                })  
                const valuePay=cotasPay.length*value;
                $('#confirmation-cotas-select').html(cotasMap);
                $('#confirmation-cotas-value').html(valuePay.toFixed(2));

                $('#confimation-pay-id').show();
                $('.backgroudAch').show();
                $("body").css("overflow", "hidden");

            }
            function confirmed() {
                const form=document.querySelector('#form-pay-cota');
                addInput();
                form.submit();
            }
            function hideConfirm() {
                $('#confimation-pay-id').hide(100);
                $('.backgroudAch').hide();
                $("body").css("overflow", "auto");
            }
            function hideResult() {
                $('.confimation-pay-class').hide(100);
                $('.backgroudAch').hide();
                $("body").css("overflow", "auto");
            }
            function addInput(){
                const input='<input name="cotasSelect" type="hidden" value="'+cotasPay+'">';
                $('#form-pay-cota').append(input);
            }

            function add(cota) {
                showSlideDown();
                const id='cota-pay'+cota;
                const id2='cota'+cota;
                const exist = cotasPay.indexOf(cota);
                if(exist===-1){
                    cotasPay.push(cota);
                    $('#cotas-pay-add').append('<div class="cota cota-pay" id="'+id+'">'+cota+'</div>');

                    $('#'+id2).addClass('cota-select');
                    const valueTotal=(cotasPay.length*value).toFixed(2);
                    $('#values-pay-add').html(cotasPay.length+" x R$ "+value+" = R$ "+valueTotal);
                }else{
                    cotasPay.splice(exist, 1);
                    $('#'+id).remove();
                    hideSlideDown();
                    $('#'+id2).removeClass('cota-select');
                    const valueTotal=(cotasPay.length*value).toFixed(2);
                    $('#values-pay-add').html(cotasPay.length+" x R$ "+value+" = R$ "+valueTotal);
                }

                console.log(cotasPay)
            }
            function showSlideDown() {
                if(cotasPay.length===0){
                    $('#calculator').slideDown(200);
                }
            }
            function hideSlideDown() {
                if(cotasPay.length===0){
                    $('#calculator').slideUp(200);
                }
            }

            function livres(status) {
                $.ajax({
                    url: "{{ route('user/sorteios-status') }}",
                    method: "post",
                    data: { 
                        id: sorteioId, 
                        _token: "{{ csrf_token() }}",
                        status: status 
                    },
                    beforeSend : function(){
                        $(".cotas").html("CARREGANDO...");
                    },
                    success: function(data) {
                        $('.cotas').html(data);
                    }
                });
            }
        </script>
    </div>
@endsection

@section('confirmation')
    <div class="confimation-pay" id="confimation-pay-id">
        <h1>Confirmar compra:</h1>
        <div class="confirmation-cotas">
            <div>Você selecionou as cotas:</div>
            <div id="confirmation-cotas-select"></div>
        </div>
        <div class="confirmation-value">Valor total: R$ <span id="confirmation-cotas-value"></span></div>
        <div class="confirmation-confirm">Realmente deseja continuar?</div>
        <div class="confirmation-buttons">
            <button onclick="confirmed()">SIM</button>
            <button onclick="hideConfirm()">NAO</button>
        </div>
    </div>
@endsection
@if (count($errors->all())>0)
    @section('resultPayCota')
        <style>
            body{
                overflow: hidden;
            }
        </style>
        @section('backgroudAch')
            <div class="backgroudAch" style="display: block"></div>
        @endsection
        @if ($errors->all()[0]=='sucess')
            <div class="confimation-pay confimation-pay-class" style="display: block">
                <h1>Reserva efetuada com sucesso :)</h1>
                <div class="confirmation-buttons">
                    <button onclick="hideResult()">OK</button>
                </div>
            </div> 
        @else
            <div class="confimation-pay confimation-pay-class" style="display: block">
                <h1>Não foi possivel realizar a reservas da cotas :(</h1>
                <div class="confirmation-buttons">
                    <button onclick="hideResult()">OK</button>
                </div>
            </div>
        @endif
    @endsection
@endif

@section('backgroudAch')
    <div class="backgroudAch"></div>
@endsection
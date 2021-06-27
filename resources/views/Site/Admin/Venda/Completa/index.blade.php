@extends('Layouts/admin')
@section('css', asset('Css/vendas.css'))
@section('content')
<style>#completa-link{color: #fff;}</style>

<?php //dd($vendas);?>
    <div class="conteiner-geral">
        @if (isset($vendas))
            <div class="header-geral">
                <div>Cotas vendidas</div>
                <div>
                    <input type="text" placeholder="Digite o nome do sorteio" id="inputFind" onkeyup="findSorteio()">
                    <img src="{{ asset('Imagens/lupa.png') }}" alt="Lupa" class="lupa" onclick="findSorteio()">
                </div>
            </div>
            <div class="conteiner-geral-itens">
                @foreach ($vendas as $venda)
                    <div class="geral-intem">
                            <span class="title-geral">Venda #{{ $venda->dataReserva }}</span>
                            <div class="body-geral">
                                <div>{{ $venda->sorteio }}</div>
                                <div>Nome: {{ $venda->name }}</div>
                                <div>Contato: {{ $venda->phone }}</div>
                                <div>Cotas selecionadas: {{ $venda->cotas }}</div>
                                <div>Número de cotas: {{ $venda->qtdCotas }}</div>
                                <div>Valor: R$ {{ $venda->valor }}</div>
                                <div>Valor total: R$ {{ $venda->valorTotal }}</div>
                            </div>
                    </div>
                @endforeach
            </div>
        @else
            <h1 class="notVendas">Não à vendas completas !</h1>
        @endif
    </div>
    <script>
        function findSorteio(){
            const find = $('#inputFind').val().trim();
            if(find!=''){
                const url = location.origin + '/admin/venda/completa/busca/'+find;
                $.ajax({
                    url: url,
                    method: "get",
                    beforeSend : function(){
                        $(".conteiner-geral-itens").html("CARREGANDO...");
                    },
                    success: function(data) {
                        $(".conteiner-geral-itens").html(data);
                    }
                });
            }
        }
    </script>
@endsection
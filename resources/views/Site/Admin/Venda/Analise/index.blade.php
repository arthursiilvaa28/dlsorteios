@extends('Layouts/admin')
@section('css', asset('Css/vendas.css'))
@section('content')
<style>#analise-link{color: #fff;}</style>

<?php //dd($vendas);?>
    <div class="conteiner-geral">
        @if (isset($vendas))
            <div class="header-geral">
                <div>Cotas para análise</div>
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
                                <a href="{{ asset('storage/comprovantes/'.$venda->comprovante) }}" target="_blank">Visualizar comprovante</a>
                            </div>
                            <div class="buttons-geral">
                                <button onclick="confirmaPay({{$venda->id}})">Confirmar</button>
                                <button onclick="recusaPay({{$venda->id}})">Recusar</button>
                            </div>
                    </div>
                @endforeach
            </div>
        @else
            <h1 class="notVendas">Não à vendas para análise !</h1>
        @endif
    </div>
    <script>
        function confirmaPay(sorteioId){
            $.ajax({
                    url: "{{ route('admin/venda/confirmaPay') }}",
                    method: "post",
                    data: { 
                        id: sorteioId, 
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        const result = JSON.parse(data);
                        if(result.error){
                            alert(result.msgError);
                        }else{

                            const data = result.data;
                            if(data==null){
                                const notVenda = "<h1 class='notVendas'>Não à vendas para análise !</h1>";
                                $('.conteiner-geral').html(notVenda);
                            }else{
                                $('.conteiner-geral-itens').html('');
                                const url=location.origin;
                                data.forEach(venda => {
                                    
                                    const img=url+"/storage/comprovantes/"+venda.comprovante;
                                    const dados = "<div class='geral-intem'><span class='title-geral'>Venda #"+venda.dataReserva+"</span><div class='body-geral'><div>"+venda.sorteio+"</div><div>Nome: "+venda.name+"</div><div>Contato: "+venda.phone+"</div><div>Cotas selecionadas: "+venda.cotas+"</div><div>Número de cotas: "+venda.qtdCotas+"</div><div>Valor: R$ "+venda.valor+"</div><div>Valor total: R$ "+venda.valorTotal+"</div><a href='"+img+"' target='_blank'>Visualizar comprovante</a></div><div class='buttons-geral'><button onclick='confirmaPay("+venda.id+")'>Confirmar</button><button onclick='recusaPay("+venda.id+")'>Recusar</button></div></div>";
                                    $('.conteiner-geral-itens').append(dados);
                                   
                                });
                            }   
                            alert(result.msgSucess);
                        }
                    }
                });
        }
        function recusaPay(sorteioId){
            $.ajax({
                    url: "{{ route('admin/venda/recusaPay') }}",
                    method: "post",
                    data: { 
                        id: sorteioId, 
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        const result = JSON.parse(data);
                        if(result.error){
                            alert(result.msgError);
                        }else{

                            const data = result.data;
                            if(data==null){
                                const notVenda = "<h1 class='notVendas'>Não à vendas para análise !</h1>";
                                $('.conteiner-geral').html(notVenda);
                            }else{
                                $('.conteiner-geral-itens').html('');
                                const url=location.origin;
                                data.forEach(venda => {
                                    
                                    const img=url+"/storage/comprovantes/"+venda.comprovante;
                                    const dados = "<div class='geral-intem'><span class='title-geral'>Venda #"+venda.dataReserva+"</span><div class='body-geral'><div>"+venda.sorteio+"</div><div>Nome: "+venda.name+"</div><div>Contato: "+venda.phone+"</div><div>Cotas selecionadas: "+venda.cotas+"</div><div>Número de cotas: "+venda.qtdCotas+"</div><div>Valor: R$ "+venda.valor+"</div><div>Valor total: R$ "+venda.valorTotal+"</div><a href='"+img+"' target='_blank'>Visualizar comprovante</a></div><div class='buttons-geral'><button onclick='confirmaPay("+venda.id+")'>Confirmar</button><button onclick='recusaPay("+venda.id+")'>Recusar</button></div></div>";
                                    $('.conteiner-geral-itens').append(dados);
                                   
                                });
                            }   
                            alert(result.msgSucess);
                        }
                    }
                });
        }
        function findSorteio(){
            const find = $('#inputFind').val().trim();
            if(find!=''){
                const url = location.origin + '/admin/venda/analise/busca/'+find;
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
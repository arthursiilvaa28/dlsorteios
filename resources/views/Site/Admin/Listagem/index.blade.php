@extends('Layouts/admin')
@section('css', asset('Css/listagem-sorteios.css'))
@section('content')
    @if (isset($sorteios))
        <div class="conteiner-list">
            <div class="header-list">
                <div>Sorteios Cadastrados</div>
                <div>
                    <input type="text" placeholder="Digite o nome do sorteio" id="inputFind" onkeyup="findSorteio()">
                    <img src="{{ asset('Imagens/lupa.png') }}" alt="Lupa" class="lupa" onclick="findSorteio()">
                </div>
            </div>
            <div class="conteiner-listagem">
                @foreach ($sorteios as $sorteio)
                    <div class="intem-listagem">
                        <div class="previu">
                            <img src="{{ asset('storage/sorteios/'.$sorteio->foto) }}" alt="img" id="previuImg">
                            <div class="dados-sorteio">
                                <div class="desc-sorteio" id="dataPreviu">{{ $sorteio->data }}</div>
                                <div class="title-sorteio" id="namePreviu">{{$sorteio->sorteio }}</div>
                                <div class="desc-sorteio">Apartir de R$ <span id="valorPreviu">{{ $sorteio->valor }}</span></div>
                            </div>
                        </div>
                        <div class="details">        
                            <div class="title-details">
                                <div>{{$sorteio->sorteio}}</div>
                                <select id="selectPublic{{ $sorteio->id }}" onchange="isPublic({{ $sorteio->id }})">
                                    @if ($sorteio->visible==0)
                                        <option value="0">Privado</option>
                                        <option value="1">Público</option>
                                    @else
                                        <option value="1">Público</option>
                                        <option value="0">Privado</option>
                                    @endif
                                </select>
                            </div>
                            <div class="conteiner-dados-details">
                                <div>
                                    <div class="dados-details">Cotas total: {{ $sorteio->numCotas }}</div>
                                    <div class="dados-details">Cotas reservadas: {{ $sorteio->dadosCotas['reservados'] }}</div>
                                    @if ($sorteio->dadosCotas['vendaAnalise']>0)
                                        <div class="dados-details dados-analise">Vendas para análise: {{ $sorteio->dadosCotas['vendaAnalise'] }}</div>
                                    @else
                                    <div class="dados-details">Vendas para análise: {{ $sorteio->dadosCotas['vendaAnalise'] }}</div>
                                    @endif
                                </div>  
                                <div>
                                    <div class="dados-details">Cotas vendidas: {{ $sorteio->dadosCotas['vendidas'] }}</div>
                                    <div class="dados-details">Cotas disponíveis: {{ $sorteio->dadosCotas['disponiveis'] }}</div>
                                </div>
                            </div>
                            <div class="buttons-details"> 
                                <a href=" {{ route('admin/sorteio/edit-sorteio-show', ['id' => $sorteio->id]) }} ">Editar</a>
                                <a href=" {{ route('admin/sorteio/excluir-sorteio', ['id' => $sorteio->id]) }} ">Excluir</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else   
        <div class="noSorteio">Não há sorteios cadastrados :(</div>
    @endif
    <script>
        function isPublic(sorteioId){
            const idSelect = '#selectPublic'+sorteioId;
            const selectPublic = $(idSelect).val();
                $.ajax({
                    url: "{{ route('admin/sorteio/update-sorteio-status') }}",
                    method: "post",
                    data: { 
                        id: sorteioId, 
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        const result = JSON.parse(data);
                        if(result.error){
                            $(idSelect).val(1);
                            alert(result.msgError);
                        }
                    }
                });
            
        }
        function hideErrorDeleted(){
            $('.errorDeleted').hide(100);
        }
        function findSorteio(){
            const find = $('#inputFind').val().trim();
            if(find!=''){
                const url = location.origin + '/admin/sorteio/sorteiosFind/' + find;
                $.ajax({
                    url: url,
                    method: "get",
                    beforeSend : function(){
                        $(".conteiner-listagem").html("CARREGANDO...");
                    },
                    success: function(data) {
                        $(".conteiner-listagem").html(data);
                    }
                });
            }
        }
    </script>
@endsection

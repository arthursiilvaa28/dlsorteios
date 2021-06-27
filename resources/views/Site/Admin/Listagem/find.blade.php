
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
                        <option value="">Privado</option>
                        <option value="">Público</option>
                    @else
                        <option value="">Público</option>
                        <option value="">Privado</option>
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
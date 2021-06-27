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
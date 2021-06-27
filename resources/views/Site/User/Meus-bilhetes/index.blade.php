@extends('Layouts/site')
@section('css', asset('Css/meusBilhetes.css'))
@section('content')
    @if (isset($cotas))
        <div class="conteiner-table">
            <div class="header-table">Seus Bilhetes</div>
            <table class="table-bilhete" border="1px">
                
                @error('sucess')
                    <div class="sucess-comprovante">{{$message }}</div>
                @enderror
                @error('comprovante')
                    <div class="error-comprovante">{{$message }}</div>
                @enderror 
                <tr class="indix-tables">
                    <th>Sorteio</th>
                    <th>Data</th>
                    <th>Cotas</th>
                    <th>qtd cotas</th>
                    <th>Valor(Un)</th>
                    <th>Valor(total)</th>
                    <th>Data da reserva</th>
                    <th>Pagamento</th>
                    <th>Comprovante</th>
                    <th>Status</th>
                </tr>
                @foreach ($cotas as $cota)
                    <tr>
                        <td>{{ $cota->sorteio}}</td>
                        <td>{{ $cota->data }}</td>
                        <td>
                            <?php 
                                $cotasSelect=explode(',', $cota->cotas);
                                $i=1;
                                foreach ($cotasSelect as $value) {
                                    if( ! ($i % 6 == 0 )){
                                        echo "$value ";
                                    }else{
                                        echo "$value <br>";
                                    }
                                    $i++;
                                }  
                            ?>
                        </td>
                        <td>{{ $cota->qtdCotas }}</td>
                        <td>{{ $cota->valor }}</td>
                        <td>{{ $cota->valorTotal }}</td>
                        <td>{{ $cota->dataReserva }}</td>
                        <td><a class="pagment-envia" href="#">Realizar Pagamento</a></td>
                        <td>
                            @if ($cota->comprovante=='Enviar')
                                <span class="enviar" onclick="enviarComprovante({{$cota->id}})">{{ $cota->comprovante }}</span>

                                <form method="POST" action="{{ route('user/comprovante')}}" style="display: none" enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" name="venda" value="{{ $cota->id }}">
                                    <input type="file" name="comprovante"  onchange="form.submit()" id="inputCota{{$cota->id}}">
                                </form>

                            @else
                                <span>Enviado</span>
                            @endif
                        </td>
                        <td class="hover-status">

                            @switch($cota->status)
                                @case('Completo')
                                    <span class="completo">{{ $cota->status }}</span>
                                    @break
                                @case('Recusado')
                                    <span class="recusado">{{ $cota->status }}</span>
                                    <div class="descripition-status">
                                        <h3>Pagamento recusado:</h3>
                                        <p>Seu pagamento foi recusado. Entre contato com nosca para mais detalhes !</p>
                                    </div>
                                    @break
                                @case('Pendente')
                                    <span class="pendente">{{ $cota->status }}</span>
                                    <div class="descripition-status">
                                        <h3>Agurdando envio de comprovante:</h3>
                                        <p>Por favor, envie comprovante do pagamento, para validar sua compra !</p>
                                    </div>
                                    @break
                                @case('Removido')
                                    <span class="">{{ $cota->status }}</span>
                                    <div class="descripition-status">
                                        <h3>Removido:</h3>
                                        <p>Sua compra foi removida, pois você não efetuou o pagamento !</p>
                                    </div>
                                    @break
                                @case('Análise')
                                    <span class="analise">{{ $cota->status }}</span>
                                    <div class="descripition-status">
                                        <h3>Aguarde:</h3>
                                        <p>Por favor, aguarde até o administrador validar seu comprovante, isso pode demorar até 24 horas.</p>
                                    </div>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <h1 class="notBilhete">Voce não possui bilhetes :(</h1>
    @endif

    <script>
        function enviarComprovante(id) {
            document.getElementById('inputCota'+id).click();
        }
    </script>
@endsection
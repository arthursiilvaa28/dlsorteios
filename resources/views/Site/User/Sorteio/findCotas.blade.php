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
                    <span>Redervado por:</span>
                    <span>{{$cota['userName']}}</span>
                </div>
            </div>
            @break
        @case('Disponivel')
            <div class="cota" id="{{'cota'.$cota['cota']}}" onclick="add({{$cota['cota']}})">{{$cota['cota']}}</div>
            @break
    @endswitch
@endforeach
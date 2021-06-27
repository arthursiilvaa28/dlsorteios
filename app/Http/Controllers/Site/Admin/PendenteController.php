<?php

namespace App\Http\Controllers\Site\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Cota;
use Illuminate\Support\Facades\DB;

class PendenteController extends Controller
{
    public function index()
    {
        $vendas= DB::table('vendas')
            ->where('status', 'Pendente')
            ->orderBy("sorteios.id", "desc")
            ->Join('sorteios', 'sorteios.id', '=', 'vendas.sorteio_id')
            ->Join('users', 'users.id', '=', 'vendas.user_id')
            ->select('vendas.*', 'sorteios.valor', 'sorteios.sorteio', 'users.name', 'users.phone')
            -> get();
        if(count($vendas)==0){
            return view('Site/Admin/Venda/Pendente/index', ['vendas' => null]);
        }
        
        foreach ($vendas as $venda) {
            $cotasSelect = str_replace(',' , " ", $venda->cotas);
            $venda -> cotas =  $cotasSelect;

            $data = str_replace('/' , "-", $venda->dataReserva);
            $dateStart = new \DateTime($data);
            $dateNow   = new \DateTime(date('d-m-Y H:i'));
            $dateDiff = $dateStart->diff($dateNow);

            $venda -> dataExpire = $dateDiff->d.' dia e '.$dateDiff->h.' hora';
        }
       
        return view('Site/Admin/Venda/Pendente/index', ['vendas' => $vendas]);
    }
    
    public function show($sorteio)
    {
        $vendas= DB::table('vendas')
            ->where('status', 'Pendente')
            ->orderBy("sorteios.id", "desc")
            ->Join('sorteios', 'sorteios.id', '=', 'vendas.sorteio_id')
            ->Where('sorteios.sorteio', 'like', '%'.$sorteio.'%')
            ->Join('users', 'users.id', '=', 'vendas.user_id')
            ->select('vendas.*', 'sorteios.valor', 'sorteios.sorteio', 'users.name', 'users.phone')
            -> get();
        if(count($vendas)==0){
            return '<div class="notVendas">Não há sorteio com esse nome ! </div>';
        }
        
        foreach ($vendas as $venda) {
            $cotasSelect = str_replace(',' , " ", $venda->cotas);
            $venda -> cotas =  $cotasSelect;

            $data = str_replace('/' , "-", $venda->dataReserva);
            $dateStart = new \DateTime($data);
            $dateNow   = new \DateTime(date('d-m-Y H:i'));
            $dateDiff = $dateStart->diff($dateNow);

            $venda -> dataExpire = $dateDiff->d.' dia e '.$dateDiff->h.' hora';
        }
       
        return view('Site/Admin/Venda/Pendente/find', ['vendas' => $vendas]);
    }

    public function remove(Request $request){
        
        $id = $request->id;
        $venda = Venda::find($id);
        if($venda==null){
            $result = array( 'error' => true, 'msgError' => 'Venda inválida');
            $json = json_encode($result);
            return $json;
        }
        if($venda->status!='Pendente'){
            $result = array( 'error' => true, 'msgError' => 'Essa venda não está pendente !');
            $json = json_encode($result);
            return $json;
        }
        $venda -> status = 'Removido';
        $venda -> save();
        //remover as cotas
        $cotas = explode(",","$venda->cotas");
        foreach ($cotas as $value) {
            $cota = Cota::where('cota', $value);
            $cota->delete();

        }

        $vendas= DB::table('vendas')
        ->where('status', 'Pendente')
        ->orderBy("sorteios.id", "desc")
        ->Join('sorteios', 'sorteios.id', '=', 'vendas.sorteio_id')
        ->Join('users', 'users.id', '=', 'vendas.user_id')
        ->select('vendas.*', 'sorteios.valor', 'sorteios.sorteio', 'users.name', 'users.phone')
        -> get();
        if(count($vendas)==0){
            $result = array( 'error' => false, 'msgSucess' => 'Venda removida com sucesso !', 'data' => null);
            $json = json_encode($result);
            return $json;
        }
        $result = array( 'error' => false, 'msgSucess' => 'Venda removida com sucesso !', 'data' => $vendas);
        $json = json_encode($result);
        return $json;
    }

}

<?php

namespace App\Http\Controllers\Site\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Cota;
use Illuminate\Support\Facades\DB;
class AnaliseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendas= DB::table('vendas')
            ->where('status', 'Análise')
            ->orderBy("sorteios.id", "desc")
            ->Join('sorteios', 'sorteios.id', '=', 'vendas.sorteio_id')
            ->Join('users', 'users.id', '=', 'vendas.user_id')
            ->select('vendas.*', 'sorteios.valor', 'sorteios.sorteio', 'users.name', 'users.phone')
            -> get();
        if(count($vendas)==0){
            return view('Site/Admin/Venda/Analise/index', ['vendas' => null]);
        }
        
        foreach ($vendas as $venda) {
            $cotasSelect = str_replace(',' , " ", $venda->cotas);
            $venda -> cotas =  $cotasSelect;
        }
        return view('Site/Admin/Venda/Analise/index', ['vendas' => $vendas]);
    }

    public function show($sorteio)
    {
        $vendas= DB::table('vendas')
            ->where('status', 'Análise')
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
        }
       
        return view('Site/Admin/Venda/Analise/find', ['vendas' => $vendas]);
    }


    public function confirmPay(Request $request){
        $id = $request->id;
        $venda = Venda::find($id);
        if($venda==null){
            $result = array( 'error' => true, 'msgError' => 'Venda inválida');
            $json = json_encode($result);
            return $json;
        }
        if($venda->status!='Análise'){
            $result = array( 'error' => true, 'msgError' => 'Essa venda não possui comprovante !');
            $json = json_encode($result);
            return $json;
        }
        $venda -> status = 'Completo';
        $venda -> dataPay =date('d/m/Y H:i');
        $venda -> save();
        $cotas = explode(",","$venda->cotas");
        foreach ($cotas as $value) {
            Cota::where('cota', $value)
                 ->update(['status' => 'Vendido']);
        }

        return $this->atualizaVenda('confirmada');
        
    }

    public function recusaPay(Request $request){
        $id = $request->id;
        $venda = Venda::find($id);
        if($venda==null){
            $result = array( 'error' => true, 'msgError' => 'Venda inválida');
            $json = json_encode($result);
            return $json;
        }
        if($venda->status!='Análise'){
            $result = array( 'error' => true, 'msgError' => 'Essa venda não possui comprovante !');
            $json = json_encode($result);
            return $json;
        }
        $venda -> status = 'Recusado';
        $venda -> save();
        //remover as cotas
        $cotas = explode(",","$venda->cotas");
        foreach ($cotas as $value) {
            $cota = Cota::where('cota', $value);
            $cota->delete();

        }
        return $this->atualizaVenda('recusada');
        
        
    }
    private function atualizaVenda($value){
        $vendas= DB::table('vendas')
            ->where('status', 'Análise')
            ->orderBy("sorteios.id", "desc")
            ->Join('sorteios', 'sorteios.id', '=', 'vendas.sorteio_id')
            ->Join('users', 'users.id', '=', 'vendas.user_id')
            ->select('vendas.*', 'sorteios.valor', 'sorteios.sorteio', 'users.name', 'users.phone')
            -> get();
        if(count($vendas)==0){
            $result = array( 'error' => false, 'msgSucess' => 'Venda '.$value.' com sucesso !', 'data' => null);
            $json = json_encode($result);
            return $json;
        }
        $result = array( 'error' => false, 'msgSucess' => 'Venda '.$value.' com sucesso !', 'data' => $vendas);
        $json = json_encode($result);
        return $json;
    }
}

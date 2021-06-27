<?php

namespace App\Http\Controllers\Site\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompletaController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendas= DB::table('vendas')
            ->where('status', 'Completo')
            ->orderBy("sorteios.id", "desc")
            ->Join('sorteios', 'sorteios.id', '=', 'vendas.sorteio_id')
            ->Join('users', 'users.id', '=', 'vendas.user_id')
            ->select('vendas.*', 'sorteios.valor', 'sorteios.sorteio', 'users.name', 'users.phone')
            -> get();
        if(count($vendas)==0){
            return view('Site/Admin/Venda/Completa/index', ['vendas' => null]);
        }
        
        foreach ($vendas as $venda) {
            $cotasSelect = str_replace(',' , " ", $venda->cotas);
            $venda -> cotas =  $cotasSelect;
        }
       
        return view('Site/Admin/Venda/Completa/index', ['vendas' => $vendas]);
    }
    public function show($sorteio)
    {
        $vendas= DB::table('vendas')
            ->where('status', 'Completo')
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
       
        return view('Site/Admin/Venda/Completa/find', ['vendas' => $vendas]);
    }
}

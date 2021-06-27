<?php

namespace App\Http\Controllers\Site\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Venda;


class MeusBilhetesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=Auth::user()->id;
        //pegando todas as cotas desse usuario.
        $cotas = DB::table('vendas')
            ->Join('sorteios', 'sorteios.id', '=', 'vendas.sorteio_id')
            ->where('user_id', $user)
            ->orderBy("vendas.id", "desc")
            ->select('vendas.*', 'sorteios.sorteio', 'sorteios.valor', 'sorteios.data')
            ->get();
        if(count($cotas)==0){
            $cotas=NULL;
        }
        return view('Site/User/Meus-bilhetes/index', ['cotas' => $cotas]);
    }

    public function comprovante(Request $request)
    {
        $credentials = $request->validate([
            'venda' => ['required', 'regex:/(^[0-9]+$)+/'],
            'comprovante' => ['required','image'],
        ], [
            'required' => 'Esse campo não pode está vazio',
        ]);

        $extension = $request->comprovante->getClientOriginalExtension();
        if(!($extension=='jpg' || $extension=='png' || $extension=='PDF')){
            return back()->withErrors(['comprovante' => 'Extençao de imagem incopativel']);
        }
        //gera um numero unico baseado no timestamp atual
        $name = uniqid();
        //salva um nome baseado no id
        $nameFile = "{$name}.{$extension}";
        // Faz o upload:
        $upload = $request->comprovante->storeAs('comprovantes', $nameFile);
        $credentials['comprovante']=$nameFile;

        $venda = Venda::find($credentials['venda']);        
        if($venda==null || $venda->status=='Análise'){
            return back();
        }
        $venda->comprovante = $credentials['comprovante'];
        $venda->status = 'Análise';
        $venda->save();
        return back()->withErrors(['sucess' => 'Comprovante enviado com sucesso !']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

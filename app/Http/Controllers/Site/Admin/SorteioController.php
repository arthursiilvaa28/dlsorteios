<?php

namespace App\Http\Controllers\Site\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sorteio;
use App\Models\Cota;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class SorteioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sorteios = Sorteio::all()->sortByDesc("id");

        
        
        if(count($sorteios)==0){
            return view('Site/Admin/Listagem/index', ['sorteios' => null]);
        }
        foreach ($sorteios as $sorteio) {
            $sorteioCotas=$this->cotas($sorteio);

            $sorteio->dadosCotas = $sorteioCotas;
        }

        return view('Site/Admin/Listagem/index', ['sorteios' => $sorteios]);
    }
    private function cotas($sorteio){

        $dadosCotas = [ 'reservados' => 0, 'vendidas' => 0, 'disponiveis' => $sorteio->numCotas, 'vendaAnalise' => 0];

        $cotas = Cota::all()->where('sorteio_id', $sorteio->id);
        foreach ($cotas as $cota) {
            if($cota->status == 'Reservado'){
                $dadosCotas['disponiveis']--;
                $dadosCotas['reservados']++;
            }else{
                $dadosCotas['disponiveis']--;
                $dadosCotas['vendidas']++;
            }
        }
        $vendas = Venda::all()->where('sorteio_id', $sorteio->id);
        foreach ($vendas as $venda) {
            if($venda->status == 'Análise'){
                $dadosCotas['vendaAnalise']++;
            }
        }

        return $dadosCotas;
    }
    public function showFindLike($sorteio)
    {
        
        $sorteios = DB::table('sorteios')
            ->Where('sorteio', 'like', '%'.$sorteio.'%') 
            ->orderBy("sorteios.id", "desc")
            -> get();
        if(count($sorteios)==0){
            return '<div class="noSorteioFind">Não há sorteio com esse nome ! </div>';
        }
        foreach ($sorteios as $sorteio) {
            $sorteioCotas=$this->cotas($sorteio);
            $sorteio->dadosCotas = $sorteioCotas;
        }
        return view('Site/Admin/Listagem/find', ['sorteios' => $sorteios]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Site/Admin/NewSorteio/index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $credentials = $request->validate([
                'numCotas' => ['required'],
                'sorteio' => ['required', 'unique:sorteios'],
                'foto' => ['required','image'],
                'valor' => ['required'],
                'data' => ['required']
            ], [
                'required' => 'Preencha todos os campos !',
                'sorteio.unique' => 'Nome de sorteio indisponível'
            ]);
            if($request->valor<=0){
                return back()->withInput()->withErrors(['numero' => 'O valor da cota inserindo é inválido']);
            }
            if($request->numCotas<=0){
                return back()->withInput()->withErrors(['numero' => 'Número de cotas inválido']);
            }
            if($this->validaData($request->data)){
                return back()->withInput()->withErrors(['data' => 'Data inválida, porfavor inserir outra data']);
            }


            list($with, $heith) = getimagesize($credentials['foto']);
            if( round($with/$heith, 2) !== round(16/9, 2)){
                return back()->withInput()->withErrors(['format' => 'Selecione uma imagem com formato válido (16:9)']);
            }
            $extension = $request->foto->getClientOriginalExtension();
            if(!($extension=='jpg' || $extension=='png' || $extension=='jpeg')){
                return back()->withInput()->withErrors(['extension' => 'Extençao de imagem incompátivel']);
            }
            //gera um numero unico baseado no timestamp atual
            $name = uniqid();
            //salva um nome baseado no id
            $nameFile = "{$name}.{$extension}";
            // Faz o upload:
            $upload = $request->foto->storeAs('sorteios', $nameFile);
    
            $credentials['foto']=$nameFile;
            $sorteio = Sorteio::create($credentials);
            return back()->withErrors(['sucess' => 'sucess', 'msg' => 'Sorteio ( '.$sorteio->sorteio.' ) criado com sucesso !']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sorteio = Sorteio::find($id);
        if($sorteio==null){
            return abort(404, 'Page not found');
        }

        if($sorteio->visible==0){
            return view('Site/Admin/Edit/index', ['sorteio' => $sorteio, 'edit' => true]);
        }
        $venda = Venda::all()->Where('sorteio_id', $id);
        if(count($venda)==0){
            return view('Site/Admin/Edit/index', ['sorteio' => $sorteio, 'edit' => true]);
        }
        return view('Site/Admin/Edit/index', ['sorteio' => $sorteio, 'edit' => false]);
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
        $sorteio = Sorteio::find($id);
        if($sorteio==null){
            return back();
        }else{
            $venda = Venda::all()->Where('sorteio_id', $id);
            if(count($venda)==0){
                $credentials=null;
                if($request->sorteio==$sorteio->sorteio){
                    $credentials = $request->validate([
                        'numCotas' => ['required'],
                        'valor' => ['required'],
                        'data' => ['required']
                    ], [
                        'required' => 'Preencha todos os campos !',
                    ]); 
                }
                if($request->sorteio!=$sorteio->sorteio){
                    $credentials = $request->validate([
                        'numCotas' => ['required'],
                        'sorteio' => ['required', 'unique:sorteios'],
                        'valor' => ['required'],
                        'data' => ['required']
                    ], [
                        'required' => 'Preencha todos os campos !',
                        'sorteio.unique' => 'Nome de sorteio indisponível'
                    ]);
                    $sorteio -> sorteio = $credentials['sorteio'];
                }
                if($request->valor<=0){
                    return back()->withInput()->withErrors(['numero' => 'O valor da cota inserindo é inválido']);
                }
                if($request->numCotas<=0){
                    return back()->withInput()->withErrors(['numero' => 'Número de cotas inválido']);
                }
                if($this->validaData($request->data)){
                    return back()->withInput()->withErrors(['data' => 'Data inválida, porfavor inserir outra data']);
                }

                $sorteio -> numCotas = $credentials['numCotas'];
                $sorteio -> valor = $credentials['valor'];
                $sorteio -> data = $credentials['data'];
                $sorteio -> save();
                return back()->withErrors(['sucess' => 'sucess', 'msg' => 'Valores alterados com sucesso !']);
            }
            else{
                $credentials = $request->validate([
                    'data' => ['required']
                ], [
                    'required' => 'Preencha todos os campos !',
                ]);
                if($this->validaData($request->data)){
                    return back()->withInput()->withErrors(['data' => 'Data inválida, porfavor inserir outra data']);
                }

                $sorteio->data = $credentials['data'];
                $sorteio->save();
                return back()->withErrors(['sucess' => 'sucess', 'msg' => 'Data alterada com sucesso !']);
            }
        }
    }
    public function updateStatus(Request $request)
    {
        $id = $request -> id;
        $sorteio = Sorteio::find($id);
        if($sorteio==null){
            $result = array( 'error' => true, 'typeError' => 'Id de sorteio inválido !' );
            $json = json_encode($result);
            return $json;
        }
        if($sorteio->visible==0){
            $sorteio -> visible = 1;
            $sorteio -> save();
            $result = array( 'error' => false);
            $json = json_encode($result);
            return $json;
        }
        $venda = Venda::all()->Where('sorteio_id', $id);
        if(count($venda)==0){
            $sorteio -> visible = 0;
            $sorteio -> save();
            $result = array( 'error' => false);
            $json = json_encode($result);
            return $json;
        }
        $result = array( 'error' => true, 'msgError' => 'Você não pode ocultar esse sorteio, pois, esse sorteio já tem cotas vendidas !' );
        $json = json_encode($result);
        return $json;
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sorteio = Sorteio::find($id);
        if($sorteio==null){
            return abort(404, 'Page not found');
        }
        $sorteio->delete();
        Storage::delete('sorteios/'.$sorteio -> foto);
        return back();
    }

    private function validaData($dat){
        $data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como referência
        $d = $data[0];
        $m = $data[1];
        $y = $data[2];
     
        // verifica se a data é válida!
        // 1 = true (válida)
        // 0 = false (inválida)
        if( strlen($y)==4 ){
            $res = checkdate($m,$d,$y);
            if ($res == 1){
                $data=$y.'-'.$m.'-'.$d;
                $agora = date('Y-m-d');

                if(strtotime($data) > strtotime($agora)){
                    return false;
                }else{
                    return true;
                }
            } else {
                return true;
            }
        }else{
            return true;
        }
        
    }
}

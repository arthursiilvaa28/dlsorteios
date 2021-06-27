<?php

namespace App\Http\Controllers\Site\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sorteio;
use App\Models\Cota;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sorteiosAll = Sorteio::all()->sortByDesc('id');
        $sorteios=[];

        if(count($sorteiosAll)==0){
            $sorteios=null;
            return view('Site/User/Index/index', ['sorteios' => $sorteios]);
        }

        foreach ($sorteiosAll as $value) {
            if($value->visible==1){
                array_push($sorteios, $value);
            }
        }
        
        if(count($sorteios)==0){
            $sorteios=null;
            return view('Site/User/Index/index', ['sorteios' => $sorteios]);
        }
        
        //tenho remover os sorteiomque estao visivel
        return view('Site/User/Index/index', ['sorteios' => $sorteios]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sorteio = Sorteio::find($id);

        if($sorteio==null || $sorteio->visible==0){
            return abort(404, 'Page not found');
        }

        $cotasFinaly=$this->cotas($sorteio);
        $qtdCotas=$this->qtdCotas($cotasFinaly);

        return view('Site/User/Sorteio/index', ['sorteio' => $sorteio, 'cotas' => $cotasFinaly, 'qtdCotas' => $qtdCotas]); 
    }
    public function pay(Request $request){
        $sorteio=$request->sorteioId;
        $cotasSelectArray=explode(',', $request->cotasSelect);
        
        $sorteio=Sorteio::find($sorteio);
        if($sorteio==null || $sorteio->visible==0){
            return back();
        }

        if($this->cotasIsValid($cotasSelectArray, $sorteio->numCotas)){
            return back();
        }

        //cotasIsExist verifica se ta disponivel.
        if($this->cotasIsExist($sorteio->id, $cotasSelectArray)){
            
            $user=Auth::user()->id;
            $cotasSelect=$request->cotasSelect;
            $qtdCotas = count($cotasSelectArray);
            $valorTotal = $sorteio->valor * $qtdCotas;
            
            $dataReserva = date('d/m/Y H:i');

            foreach ($cotasSelectArray as $value) {
                //padrao vai ser reservado
                $cota = Cota::create([
                    'user_id' => $user,
                    'sorteio_id' => $sorteio->id,
                    'cota' => $value,
                    'status' => 'Reservado'
                ]);
            }
            $venda = Venda::create([
                'user_id' => $user,
                'sorteio_id' => $sorteio->id,
                'cotas' => $cotasSelect,
                'qtdCotas' => $qtdCotas,
                'valorTotal' => $valorTotal,
                'dataReserva' => $dataReserva,
                'dataPay' => '-',
                'comprovante' => 'Enviar',
                'status' => 'Pendente',
            ]);

            return back()->withErrors(['sucess' => 'sucess']);

        }else{
            return back()->withErrors(['sucess' => 'error']);
        }

    }
    private function cotasIsValid($cotasSelectArray, $numCotas){    
        foreach ($cotasSelectArray as $cota) {
            try {
                $cota=$cota+0;
                if( !(is_int($cota) && $cota>=1 && $cota<=$numCotas) ){
                    return true;
                }
            } catch (\Throwable $th) {
                return true;
            }
        }
        return false;
    }

    private function cotasIsExist($sorteio, $cotasSelectArray){
        $cotas=Cota::where('sorteio_id', $sorteio)->get();
        //cotas registradas...
        for ($i=0; $i < count($cotas); $i++) { 
            for ($j=0; $j < count($cotasSelectArray); $j++) { 
                if($cotas[$i]->cota == $cotasSelectArray[$j]){
                    return false;
                }
            }
        }
        return true;
    }


    public function qtdCotasStatus(Request $request){
        $id=$request->id;
        $status=$request->status;

        $sorteio = Sorteio::find($id);
        $cotasFinaly=$this->cotas($sorteio);

        $cotasLivres=[];
        for( $i = 1; $i <= count($cotasFinaly);$i++){
            if($cotasFinaly[$i]['status'] == $status){
                $cotasLivres[$i]=$cotasFinaly[$i];
            }
        }
        return view('Site/User/Sorteio/findCotas', ['cotas' => $cotasLivres]); 
    }
    private function qtdCotas($cotasFinaly){
        $livres=0;
        $vendidos=0;  
        $reservados=0;      
        foreach ($cotasFinaly as $value) {
            if($value['status']=='Vendido'){
                $vendidos++;
            }else if($value['status']=='Reservado'){
                $reservados++;
            }else{
                $livres++;
            }
        }
        $qtdCotas=array($livres, $vendidos, $reservados);
        return $qtdCotas;
    }

    private function cotas($sorteio){
        $cotasFinaly=[];
        $cotas = DB::table('cotas')
            ->join('users', 'users.id', '=', 'cotas.user_id')
            ->where('sorteio_id', $sorteio->id)
            ->get();
        for( $i = 1; $i <= $sorteio->numCotas; $i++){
            $cotasFinaly[$i]=[
                'cota' => $i,
                'status' => 'Disponivel',
                'user' => NULL
            ];
        }
        foreach ($cotas as $value ) {
            $cotasFinaly[$value->cota]=[
                'cota' => $value->cota,
                'status' => $value->status,
                'userName' => $value->name
            ];
        }
        return $cotasFinaly;
    }
}

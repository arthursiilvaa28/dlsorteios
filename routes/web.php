<?php

use Illuminate\Support\Facades\Route;
//acount
use App\Http\Controllers\Site\Account\LoginController;
use App\Http\Controllers\Site\Account\RegisterController;

// adm
use App\Http\Controllers\Site\Admin\SorteioController;
use App\Http\Controllers\Site\Admin\AnaliseController;
use App\Http\Controllers\Site\Admin\PendenteController;
use App\Http\Controllers\Site\Admin\CompletaController;
use App\Http\Controllers\Site\Admin\VisualadmController;

//user comun
use App\Http\Controllers\Site\User\HomeController;
use App\Http\Controllers\Site\User\ContatosController;
use App\Http\Controllers\Site\User\MeusBilhetesController; 
use App\Http\Controllers\Site\User\ProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'account', 'as' => 'account/'], function(){
    Route::get('login', [LoginController::class, 'index'])->name("login");
    Route::post('login', [LoginController::class, 'login'])->name("login/do");
    Route::post('/logout', [LoginController::class, 'logout'])->name("logout");

    Route::get('/register', [RegisterController::class, 'index'])->name("register");
    Route::post('/register', [RegisterController::class, 'register'])->name("register/do");
});

Route::group(['as' => 'user/'], function(){

    Route::get('/', [HomeController::class, 'index'])->name("index");
    Route::get('/sorteio/{id}', [HomeController::class, 'show'])->name("show-sorteio");
    
    //rota utilizada via  ajax
    Route::post('/qtdCotasStatus', [HomeController::class, 'qtdCotasStatus'])->name('sorteios-status');

    //so acessa se estiver logado.
    Route::post('/payment', [HomeController::class, 'pay'])->middleware('auth')->name("payment");
    Route::get('/meus-bilhetes', [MeusBilhetesController::class, 'index'])->middleware('auth')->name("meus-bilhetes");
    Route::post('/meus-bilhetes/comprovante', [MeusBilhetesController::class, 'comprovante'])->middleware('auth')->name("comprovante");

    Route::get('/contatos', ContatosController::class)->name("contatos");
    Route::get('/Perfil',[ProfileController::class, 'index'])->middleware('auth')->name ("profile");
    Route::post('/Perfil/do', [ProfileController::class, 'update'])->middleware('auth')->name ("nvsenha");
    Route::get('/Admin/Listagem', [VisualadmController::class, 'index'])->middleware('auth')->name ("visualadm");
});

Route::group(['prefix' => 'admin', 'as' => 'admin/', 'middleware' => ['auth','admin']], function(){
    Route::get('/', [SorteioController::class, 'index']);
    Route::group(['prefix' => 'sorteio', 'as' => 'sorteio/'], function(){
        //exebir sorteios
        Route::get('/', [SorteioController::class, 'index'])->name("index");
        Route::get('/sorteiosFind/{id}', [SorteioController::class, 'showFindLike']);
        //criar sorteio
        Route::get('/new-sorteio', [SorteioController::class, 'create'])->name("new-sorteio");
        Route::post('/new-sorteio/do', [SorteioController::class, 'store'])->name("new-sorteio/do");
        //editar sorteio
        Route::get('/edit-sorteio/{id}', [SorteioController::class, 'edit'])->name("edit-sorteio-show");
        Route::post('/edit-sorteio/{id}/do', [SorteioController::class, 'update'])->name("edit-sorteio");
        Route::post('/edit-sorteio/update/status', [SorteioController::class, 'updateStatus'])->name("update-sorteio-status");
        //excluir sorteio
        Route::get('/excluir-sorteio/{id}', [SorteioController::class, 'destroy'])->name("excluir-sorteio");

        Route::get('/vendas-sorteio', function (){ return 'vendas sorteio'; })->name("vendas-sorteio");
    });
    
    Route::group(['prefix' => 'venda', 'as' => 'venda/'], function(){
        Route::get('/analise', [AnaliseController::class, 'index'])->name("analise");
        Route::post('/analise/confirm', [AnaliseController::class, 'confirmPay'])->name("confirmaPay");
        Route::post('/analise/recusa', [AnaliseController::class, 'recusaPay'])->name("recusaPay");
        Route::get('/analise/busca/{id}', [AnaliseController::class, 'show']);

        Route::get('/pendente', [PendenteController::class, 'index'])->name("pendente");
        Route::post('/pendente/remove', [PendenteController::class, 'remove'])->name("remove");
        Route::get('/pendente/busca/{id}', [PendenteController::class, 'show']);

        Route::get('/completa', [CompletaController::class, 'index'])->name("completa");
        Route::get('/completa/busca/{id}', [CompletaController::class, 'show']);
        
        
    });
});
    
    

<?php

namespace App\Http\Controllers\Site\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContatosController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('Site/User/Contatos/index');
    }
}

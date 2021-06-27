<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;
    protected $fillable = [
        'sorteio_id',
        'user_id',
        'cotas',
        'qtdCotas',
        'valorTotal',
        'dataReserva',
        'dataPay',
        'comprovante',
        'status'
    ];
}

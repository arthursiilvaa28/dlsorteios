<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cota;
use App\Models\Venda;
class Sorteio extends Model
{
    use HasFactory;
    protected $fillable = [
        'sorteio',
        'foto',
        'data',
        'valor',
        'numCotas'
    ];
    public function cotas()
    {
        return $this->hasMany(Cota::class);
    }
}

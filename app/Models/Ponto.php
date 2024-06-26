<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
    use HasFactory;

  protected  $fillable=[
        'id',
        'data_hora_inicial',
        'data_hora_final',
        'minutos_trabalhados_dia',
        'user_id',
        'tipo',
        'empresa_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models\Procesos;

use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    protected $table="sts_proces_estatus";

    protected $casts = [
        'formulario' => 'array'
    ];
    //
}

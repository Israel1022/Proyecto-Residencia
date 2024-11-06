<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table="sts_emp_configuraciones";
    protected $casts =['descripcion' => 'array'];

    protected $hidden = ['created_at','updated_at'];

        
}

<?php

namespace App\Models\Procesos;

use Illuminate\Database\Eloquent\Model;

class TipoProceso extends Model
{
    protected $table = "sts_proces_procesos";

    //
    public function Procesos()
    {
        return $this->hasMany('App\Models\Procesos\Proceso', 'tipo_proceso_id', 'id');
    }

    public function Empresa()
    {
        return $this->hasOne('App\Models\Empresa\Empresa', 'id', 'empresa_id');
    }
}

<?php

namespace App\Models\Empresa;


use Illuminate\Database\Eloquent\Model;

class TipoActividad extends Model
{
    protected $table="sts_emp_tipos_actividades";
    //
    public function TipoProceso(){
        return $this->hasOne('App\Models\Procesos\TipoProceso','id','tipo_proceso_id');
    }
    public function Proceso(){
        return $this->hasMany('App\Models\Procesos\Proceso','tipo_proceso_id','tipo_proceso_id');
    }

    public function Estatus(){
         return $this->hasMany('App\Models\Procesos\Estatus','tipo_actividad','id')->where('nombre','<>','');
    }
}

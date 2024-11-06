<?php

namespace App\Models\Procesos;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $table="sts_proces_configuracion";
    //
    
    public function TipoProceso()
    {
        return $this->belongsTo(TipoProceso::class,'tipo_proceso_id','id');
    }
    public function StatusStart()
    {
        return $this->belongsTo('App\Models\Procesos\Estatus','estatus_actual_id','id');
    }
    public function StatusFinish()
    {
        return $this->belongsTo('App\Models\Procesos\Estatus','estatus_final_id','id');
    }
}

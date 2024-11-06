<?php

namespace App\Models\Procesos;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table="sts_proces_movimientos";
    protected $appends = ['actions','estatus'];
    protected $casts =['descripcion' => 'array'];

    public function Estatus(){
        return $this->hasOne('App\Models\Procesos\Estatus','estatus_id','id');
    }
    public function Detalle(){
        return $this->hasOne('App\Models\Procesos\Detalle','movimiento_id','id');
    }
    public function Detalles(){
        return $this->hasMany('App\Models\Procesos\Detalle','movimiento_id','id');
    }

    public function getActionsAttribute()
    {
        $status = Proceso::with('StatusStart','StatusFinish')->where('estatus_actual_id',$this->attributes['estatus_id'])->get();

        $actions = [];
        foreach ($status as $action) {
            $accesAction = \Auth::user()->can($action->permiso);
            if ($accesAction)
                array_push($actions,$action);
        }
        return $actions;
    }
    public function getEstatusAttribute()
    {
        $status = Estatus::where('id',$this->attributes['estatus_id'])->first();
        return $status;
    }
}

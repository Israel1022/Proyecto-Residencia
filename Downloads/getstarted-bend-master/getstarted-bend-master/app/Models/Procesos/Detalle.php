<?php

namespace App\Models\Procesos;

use App\Models\Empresa\Empleado;
use App\Models\Inventarios\Inventario;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = "sts_proces_detalles";
    protected $casts = ['descripcion' => 'array'];

    public function Movimiento()
    {
        return $this->hasOne('App\Models\Procesos\Movimiento', 'id', 'movimiento_id');
    }

    //
    public function getDescripcionAttribute()
    {
        if (is_object($this->attributes['descripcion']))
            return $this->attributes['descripcion'];

        $descripcion = json_decode($this->attributes['descripcion']);

        if (isset($descripcion->empleado_id)) {
            $empleado = Empleado::with('Departamento')->where('id', $descripcion->empleado_id)->first();
            $descripcion->empleado = $empleado;
        }
        if (isset($descripcion->entrega_id)) {
            $entrega = Empleado::with('Departamento')->where('id', $descripcion->entrega_id)->first();
            $descripcion->entrega = $entrega;
        }

        // if (isset($descripcion->insumos)) {
        //     $insumos = [];
        //     foreach ($descripcion->insumos as $value) {
        //         if (isset($value->insumo_id)) {
        //             $invetario = Inventario::where('id', $value->insumo_id)->first();
        //             $value->inventario = $invetario;
        //             $value->nombre = $invetario->NombreEquipo;
        //         } else if (isset($value->insumo_id)) {
        //             $invetario = Inventario::where('id', $value->inventario_id)->first();
        //             $value->inventario = $invetario;
        //             $value->nombre = $invetario->NombreEquipo;
        //         }
        //         array_push($insumos, $value);
        //     }
        //     $descripcion->insumos = $insumos;
        // }
        if (isset($descripcion->inventario_id)) { //recibe
            $invetario = Inventario::where('id', $descripcion->inventario_id)->first();
            $descripcion->inventario = $invetario;
        }
        return $this->attributes['descripcion'] = $descripcion;
    }
}

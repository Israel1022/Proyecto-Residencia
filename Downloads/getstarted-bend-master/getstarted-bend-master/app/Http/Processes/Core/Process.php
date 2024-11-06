<?php

namespace App\Http\Processes\Core;

use App\Models\Empresa\Foliador;
use App\Models\Inventarios\Inventario;
use App\Models\Procesos\Detalle;
use App\Models\Procesos\Movimiento;
use App\Models\Procesos\Proceso;

class Process
{
    private $object = null;
    protected $process_id = 0; //id del tipo de proceso en tabla proces_tipos_procesos
    protected $foliador = '';

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($model = null)
    {
        $this->object = $model;
    }

    protected function IsNotNullObject()
    {
        if ($this->object != null) {
            return true;
        }
        return false;
    }

    protected function getLastMovement()
    {
        if ($this->object != null) {
            return $this->object->movements[$this->object->movements->count() - 1];
        }
        return null;
    }

    protected function getDetalles()
    {
        if ($this->object != null && isset($this->object->detalles)) {
            return $this->object->detalles;
        }
        return [];
    }

    protected function existence()
    {
        $cantidad = 0;
        foreach ($this->object->LastMovement->detalles as $detalle) {
            $descripcion = $detalle->descripcion;
            if (isset($descripcion->cantidad)) {
                $cantidad += intval($descripcion->cantidad);
            }
        }
        return $cantidad;
    }

    protected function getNextProcess($estatus_actual_id, $estatus_final_id = null)
    {
        $proceso = Proceso::with('StatusStart')->with('StatusFinish')
            ->where('tipo_proceso_id', $this->process_id)
            ->where('estatus_actual_id', $estatus_actual_id)
            ->when(isset($estatus_final_id), function ($q) use($estatus_final_id) {
                return $q->where("estatus_final_id", $estatus_final_id);
            })
            ->first();

        return $proceso;
    }

    public function getProcessDefault($id)
    {
        return Proceso::with('StatusStart')->with('StatusFinish')->where('estatus_actual_id', $id)->get();
    }

    public function saveMovement($folio, $padre_id, $estatus)
    {
        $mov = new Movimiento();
        $mov->folio = $folio;
        $mov->padre_id = $padre_id;
        $mov->estatus_id = $estatus;

        $mov->save();

        return $mov;
    }

    public function saveDetailMovement($movimiento, $descripcion)
    {
        $detail = new Detalle();
        $detail->movimiento_id = $movimiento;
        $detail->descripcion = $descripcion;

        $detail->save();

        return $detail;
    }

    // Consulta a tabla procesos.
    // El id esta vacio o null => default 1 devolvera el proceos a ejecutar.
    public function nextOptions($status_id)
    {
        try {
            $process = $this->getNextProcess($status_id);
            return $process;
        } catch (Exception $e) {
            return array('message' => $e->getMessage(), 'code' => 204, 'data' => []);
        }
    }

    public function executeAction($action, $param)
    {
        return $this->{$action}($param);
    }


    public function getLastFolio()
    {
        $folio = Foliador::where('folio', $this->foliador)->first();
        return $folio->ultimofolio;
    }

    public function getNextFolio()
    {
        return Foliador::{strtolower($this->foliador)}();
    }

    /**======================= ======================= =========== ======================= ======================= **/
    /**======================= ======================= Inventarios ======================= ======================= **/
    /**======================= ======================= =========== ======================= ======================= **/

    /**
     * function
     * El metodo devolvera el objeto con estatus Diagnosticado
     * @param [type] $param
     * @return Object
     */
    public function InputAndOutputInsumo($estatusIn, $estatusOut, $insumo_id, $des)
    {
        $invetario = Inventario::with(['LastMovement' => function ($query) use ($estatusIn) {
            $query->with('Detalles')->where('estatus_id', $estatusIn);
        }])
            ->where('id', $insumo_id)
            ->first();

        $des['cantidad'] = ($des['cantidad'] * -1);
        $this->saveDetailMovement($invetario->LastMovement->id, $des);

        $mov = Movimiento::where('folio', $invetario->folio)->where('estatus_id', $estatusOut)->first();
        if ($mov == null)
            $mov = $this->saveMovement($invetario->folio, 0, $estatusOut);

        $des['cantidad'] = ($des['cantidad'] * -1);
        $this->saveDetailMovement($mov->id, $des);
    }
}

<?php

namespace App\Http\Processes;

use App\Http\Processes\Core\Process;
use App\Models\Empresa\Foliador;
use Exception;

class ExampleProcess extends Process
{
    //Indicar el Id de la tabla procesos correspondiente al proceso que hace referencia a la clase
    protected $process_id = 0;

    public function __construct($object = null)
    {
        $this->setObject($object);
        // Indicar el foliador que se empleara. se definen en el modelo foliador
        $this->foliador = Foliador::EJEMPLO;
    }

    private function toAction($param)
    {
        try
        {
            return response()->json(array('message' => '', 'code' => 200, 'data' => null));
        } catch (Exception $e) {
            return response()->json(array('message' => $e->getMessage(), 'code' => 204, 'data' => null));
        }
    }
}

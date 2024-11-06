<?php

namespace App\Http\Controllers\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Procesos\Proceso;
use App\Models\Procesos\TipoProceso;
use Exception;
use Illuminate\Http\Request;

class TipoProcesoController extends Controller
{
    /**
     * metodo para obtener todos los registros
     * parametros entrada: sin prametros
     * parametros salida: lista de las marcas
     * (los codigos podria variar dependiento del proceso)
     */
    public function list()
    {
        $tipoProceso = TipoProceso::with('Empresa')->with(['Procesos' => function ($query) {
                    $query->with('StatusStart', 'StatusFinish');
                }])
            ->where('activo', 'si')
            ->get();
        return response($tipoProceso); // $this->listObject('App\Models\Procesos\TipoProceso',['Procesos', 'Empresa']);
    }

    /**
     * metodo para Crear o Actuailizar 1 registro de la tabla
     * parametros entrada:
     * objeto => crea o edita
     * si el "id" esta definido => actualizar
     * si el "id" no esta definido => crea
     *
     * parametros salida:
     * devuelve el objeto ya modificado o creado.
     * (los codigos podria variar dependiento del proceso)
     */
    public function createOrUpdate(Request $request)
    {
        $response= array();
        try {
            $tipoProceso = (isset($request['id'])) ? TipoProceso::find($request['id']) : new TipoProceso();
            // $tipoProceso->padre_id = $request['padre_id'];
            $tipoProceso->nombre = $request['nombre'];
            $tipoProceso->empresa_id = $request['empresa_id'];
            // $tipoProceso->activo ='si';
            $tipoProceso->save();
            $model = TipoProceso::find($tipoProceso->id);
            return response($model);
        }
        catch (Exception $e) {
            $tipoProceso = new TipoProceso();
            $response= array('message'=>$e->getMessage(),'code'=>204, 'data'=>$tipoProceso);
            return $response;
        }
    }

    /**
     * metodo para eliminar 1 o n registros de la tabla
     * parametros entrada:
     * "0" => Eliminar todos
     * "1,2,3,n" ó "1" => se eliminara los que se indica
     *
     * parametros entrada:
     * devuelve objeto con mensaje y codigo de confirmacion
     * (los codigos podria variar dependiento del proceso)
     *
     * Nota: Solo se aplicara para los acceso de super administrador
     */
    public function delete($id)
    {
        return $this->deleteObject('App\Models\Procesos\TipoProceso',$id);
    }

    /**
     * Este metodo es para los borrados logicos, cuando existe el campo "activo" en la BD
     *
     * Nota: solo los super administratores pueden activar registros borrados logicamente.
     */
    public function activateOrDesactivate ($id)
    {
        return $this->activateOrDesactivateObject('App\Models\Procesos\TipoProceso',$id);
    }

    public function ProcesoNotificacion (Request $request)
    {
        $proceso = Proceso::with('StatusStart', 'StatusFinish')->find($request->id);
        $proceso->notificar =  $request->notificar;
        $proceso->save();

        return response($proceso);
    }

}


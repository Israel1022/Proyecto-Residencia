<?php

namespace App\Http\Controllers\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Procesos\Estatus;
use Exception;
use Illuminate\Http\Request;

class EstatusController extends Controller
{
     /**
     * metedo para obtener todos los registros
     * parametros de entrada: sin parametros
     * parametros de salida: lista de tipos de datos
     * (los codigos podrían variar dependiento del proceso) 
     */
    
    public function list(){
        return $this->listObject('App\Models\Procesos\Estatus');
    }

    /**
     * metodo para Crear o Actuailizar registros en la tabla
     * parametros entrada:
     * objeto => crea o edita
     * si el "id" esta definido => actualizar
     * si el "id" no esta definido => crea
     * 
     * parametros salida:
     * devuelve el objeto ya modificado o creado.
     * (los codigos podria variar dependiento del proceso)
     */

    public function createOrUpdate(Request $request){
        try {
            $estatus = new Estatus();
            if( isset( $request['id'] ) ){
                $estatus = Estatus::where('id', $request['id'])->first();
            }
            $estatus->estatus = $request['estatus'];
            $estatus->formulario = $request['formulario'];
            $estatus->color = $request['color'];
            $estatus->empresa_id = $request['empresa_id'];
            $estatus->save();

            $message=isset($request['id'])?'Se actualizo correctamente el registro':'Se creo correctamente el registro';
            return array(
                'message'=> $message,
                'code'=>200,
                'data'=> $estatus
            );
        } catch (Exception $e) {
            $estatus = new Estatus();
            return array(
              'message'=> $e->getMessage(),
              'code'=>204,
              'data'=> $estatus
            );
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
     * (los codigos podria variar dependiendo del proceso)
     * 
     * Nota: Solo se aplicara para los acceso de super administrador
     */
    public function delete($id)
    {
        return $this->deleteObject('App\Models\Procesos\Estatus',$id);
    }
    
    /**
     * Este metodo es para los borrados logicos, cuando existe el campo "activo" en la BD
     * 
     * Nota: solo los super administratores pueden activar registros borrados logicamente.
     */
    public function activateOrDesactivate($id)
    {
        return $this->activateOrDesactivateObject('App\Models\Procesos\Estatus',$id);
    }

}


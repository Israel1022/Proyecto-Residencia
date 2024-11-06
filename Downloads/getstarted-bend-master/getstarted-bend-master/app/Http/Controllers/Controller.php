<?php

namespace App\Http\Controllers;

use App\Models\Empresa\Foliador;
use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    /**
     * metodo para obtener todos los registros de cualaquier modelo
     * @var $model object
     * @var $relationsModels string/array
     */
    public function listObject($model, $relationsModels = null)
    {
        try {
            if($relationsModels)
                return $model::with($relationsModels)->where('activo', 'si')->get();
            else
                return $model::where('activo', 'si')->get();
        }
        catch(Exception $e){
            return [];
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
    public function deleteObject($model,$id)
    {
      try {
        if ($id != "all") {
          $delete = $model::destroy($id);
          return $delete;
        } else {
          $delete = $model::query()->delete();
          return $delete;
        }
      }catch (Exception $e) {
        return ['message'=>$e->getMessage()];
      }
    }
    /**
     * Este metodo es para los borrados logicos, cuando existe el campo "activo" en la BD
     *
     * Nota: solo los super administratores pueden activar registros borrados logicamente.
     */
    public function activateOrDesactivateObject($model,$id) {
      try {
        $objet = $model::where('id', $id)->first();
        $objet = $this->switchFieldEnumYesOrNot($objet,'activo');
        return response($objet);
      } catch(Exception $e) {
        return response(['message'=>$e->getMessage()]);
      }
    }

    /**
     * Este metodo es para el bloquue de registros, cuando existe el campo "habilitado" en la BD
     *
     * Nota:dependera de los permisos asignados al usuario.
     */
    public function enableOrDisableObject($model,$id){
      try {
          $objet = $model::where('id', $id)->first();
          $objet = $this->switchFieldEnumYesOrNot($objet,'activo');
          return response($objet);
      }
      catch(Exception $e){
          return response(['message'=>$e->getMessage()]);
      }
  }

    /**
     * Este metodo es para hacer el cambio de valor de "si" o "no", solo aplicar para los enums
     *
     * Nota: aplicar para todos los metodos.
     */
    private function switchFieldEnumYesOrNot ($objet,$field){
      $objet->{$field} = ($objet->{$field} == 'si')? 'no':'si';
      $objet->save();
      return $objet;
    }

    /**
     * Crea el folio siguinete dependiendo del nombre que se le pase como parametro
     * @var $name
     * @return string valor del siguiente folio
     */
    public function getNextFolio($name)
    {
        $additionFolio = 1;
        $folio = Foliador::where('folio', $name)->first();
        $folioNew = intval($folio->ultimofolio) + $additionFolio;
        $folio->ultimofolio = $folioNew;
        $folio->save();
        return  $folio->exprecion."-".$folioNew;
    }

    public function messages() {
        return [
            'required' => 'El campo :attribute no debe ser vacío.',
            'unique' => 'El campo :attribute ya tiene un registro previo.',
            'regex' => 'La cuenta debe ser mayor a 999 y menor a 10,000'
        ];
    }

    public function validations(Request $request, $validate) {
        $validator = Validator::make($request->all(),$validate,$this->messages());
        if ($validator->fails()) {
            $msj = implode(',',$validator->errors()->all());
            throw new Exception($msj);
        }
    }
}

<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Foliador extends Model
{
    protected $table="sts_emp_foliadores";
    //
    protected $hidden = ['created_at', 'updated_at'];

    //En este ejemplo, en la base de datos debe existir un registro en la tabla foliadores con el campo folio igual a EJ
    const EJEMPLO = 'EJ';

    public function scopeEJ($query)
    {
        return $this->next($query, self::EJEMPLO);
    }

    private function next($query, $type)
    {
        //Se obtiene el tipo de folio solicitado
        $result = $query->where('folio', $type)->first();
        //Se actualiza el contador del folio
        $folio = $this->{$result->tipo_folio}($result);
        $folio->save();

        /********************************************************************************************************************/ 
        /*Para armar los numero de folio se cuntad con 3 variables que se definen en la tabla "sts_emp_foliadores" que son:
            "identity"  = identificador del folio definido en el campo "folio"
            "year"      = fecha actual
            "folio"     = número de folio que corresponde al registro definido en el campo "ultimofolio"

        La forma del folio y las variables antes memcionadas se definen en el campo "exprecion" con la estructura json siguiente:
            {"replace" : [ "{identity}" , "{year}", "{folio}" ], "expresion" : " {identity}{year}-{folio}" }
            en donde:
            "replace"   = define las variables a utilizar
            "expresion" = define la forma del folio y en donde se pueden colocar los caracteres que se deseen para conformar el folio, 
                          en el ejemplo el folio es de la forma ST20240201-01
        */
        /*******************************************************************************************************************/
        //Se obtiene la fecha actual
        $year = date("Ymd");

        //obtencion de las variables a reemplazar y la forma del folio
        $variables_expresion =json_decode($result->exprecion);
        //Generación del folio
        $generate = str_replace($variables_expresion->replace, [$result->folio, $year, $result->ultimofolio], $variables_expresion->expresion);
        return $generate;
    }


    private function secuencial($folio)
    {
        $add = 1;
        $folio->ultimofolio = intval($folio->ultimofolio) + $add;
        return $folio;
    }
    private function aleatorio($folio)
    {
        $folio->ultimofolio = Str::uuid();
        return $folio;
    }
}

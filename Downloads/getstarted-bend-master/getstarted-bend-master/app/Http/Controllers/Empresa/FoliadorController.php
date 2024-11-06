<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Foliador;
use DateTime;

class FoliadorController extends Controller
{
    public function findFoliosAll()
    {
        $folios = Foliador::where('activo','si')->get();
        return response()->json($folios);
    }
    public function findFoliosByname($name)
    {
        $foliador = Foliador::where('folio', $name)->first();
        $folioNew = intval($foliador->ultimofolio) + 1;
        // $folio->ultimofolio = $folioNew;
        $year = date("Ymd-His"); //date("Y-m-d H:i:s");
        $folio = str_replace(['{identity}','{year}','{folio}'],[$foliador->folio,$year,$folioNew], $foliador->exprecion);
        // $folio->save();
        return $folio;
    }

}

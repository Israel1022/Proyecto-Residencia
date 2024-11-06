<?php

namespace App\Http\Controllers\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Procesos\Estatus;
use App\Models\Procesos\Movimiento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientosController extends Controller
{

    public function list(Request $request)
    {
        $folio = $request->folio;
        $movs = $request->movs;
        $movimientos = Movimiento::query()->with('Detalle')->where('folio', $folio);
        if ($movs != 'todos') {
            $movimientos->latest()->take(5);            
        }
        return response()->json($movimientos->get());
    }

}


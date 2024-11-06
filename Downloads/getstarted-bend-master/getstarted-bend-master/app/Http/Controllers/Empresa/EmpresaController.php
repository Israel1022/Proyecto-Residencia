<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Configuracion;
use App\Models\Empresa\Empresa as EmpresaModel;
use App\Models\Empresa\Usuario;
use App\Notifications\StatusNotification;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{

    public function findCompanisAll()
    {
        $folios = EmpresaModel::where('activo', 'si')->get();
        return response()->json($folios);
    }

    public function emailTest(Request $request)
    {
        $usuario = new Usuario();
        $usuario->email = $request->email;
        $usuario->usuario = $request->name;
        $usuario->notify(new StatusNotification(['estatus' => 'Enviado', 'message' => $request->name, 'titulo' => 'titulo test usuario: ' . $request->name]));

        return response(['result' => 'OK']);
    }

    public function logos()
    {
        $config = Configuracion::where('nombre', 'LOGOS_ICONS_SYSTEMS')->first();
        return response($config);
    }
}

<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Configuracion;
use App\Models\Empresa\TipoActividad;

class TipoActividadController extends Controller
{
    public function list()
    {
        $actividades = TipoActividad::with('TipoProceso')->where('activo', 'si')->get();

        return response()->json($actividades);
    }
    public function listSolicitudes()
    {
        // $user = \Auth::user();
        // $role = $user['roles'][0];
        // $permissAll = isset($role->descripcion['special']) ? $role->descripcion['special'] : '';
        // $activities = [];
        $config = Configuracion::where('nombre', 'MESA_AYUDA_SOLICITUDES')->first();
        $model = TipoActividad::with('TipoProceso')
            ->whereIn('id', $config->descripcion['actividades'])
            ->where('activo', 'si')
            ->get();
        // if ($permissAll == 'no-access') {
        //     return $activities;
        // } else if ($permissAll == '') {
        //     foreach ($model as $activity) {
        //         $permissAccess = \Auth::user()->can(trim($activity->permiso));
        //         if ($permissAccess) array_push($activities, $activity);
        //     }
        // } else {
        //     $activities = $model;
        // }
        return response($model);
    }

    public function listInventarios()
    {
        $activities = [];
        $config = Configuracion::where('nombre', 'INVENTARIOS_SOLICITUDES')->first();
        $model = TipoActividad::with('TipoProceso')
            ->whereIn('id', $config->descripcion['actividades'])
            ->where('activo', 'si')
            ->get();
        foreach ($model as $activity) {
            $permissAccess = \Auth::user()->can($activity->permiso);
            if ($permissAccess)
                array_push($activities, $activity);
        }
        return response($activities);
    }

    public function listStatusInsumo()
    {
        $config = Configuracion::where('nombre', 'ALMACEN_STATUS')->first();
        $statusids = $config->descripcion['statusids'];
        $status = TipoActividad::with('TipoProceso')->where('id', 2)
            ->with(['Estatus' => function ($query) use ($statusids) {
                $query->whereIn('id', $statusids);
            }])
            ->first();

        return response()->json($status);
    }
}

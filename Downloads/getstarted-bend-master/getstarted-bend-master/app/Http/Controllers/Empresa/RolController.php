<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Rol;
use Illuminate\Http\Request;
use Exception;

class RolController extends Controller
{

    public function createOrUpdate(Request $request)
    {
        $rol = ($request->id)? Rol::find($request->id) : new Rol;
        $rol->nombre = $request->nombre;
        $rol->slug = $request->slug;
        $rol->descripcion = '{}';

        try {
            if ($rol->save()) {
                return response(Rol::find($rol->id));
            } else {
                return response(['message' => 'Favor de revisar los datos']);
            }
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        $rol = Rol::query()->with('Permisos');

        $rol->when($request->id === "ENABLE", function ($q) {
            return $q->where("activo", "si");
        });

        $rol->when($request->id === "DISABLED", function ($q) {
            return $q->where("activo", "no");
        });

        $rol->when($request->id > 0, function ($q) use ($request) {
            return $q->find($request->id);
        });

        return response($rol->get());
    }

    public function EnableOrDisable($id)
    {
       return $this->enableOrDisableObject(Rol::class,$id);
    }

    public function destroy($id)
    {
        $res=Rol::find($id)->delete();
       return $this->enableOrDisableObject(Rol::class,$id);
    }

    /* Método para asignar permisos a los roles de usuario */
    // Ej.  {"rol_id": 1,"permiso_id": 2,"special": "","check": false}
    // special : [ 'all-access' -> acceso total | 'no-access' -> sin acceso | null ]
    // check: [ true | false]  -> determina si el permiso será puesto o eliminado
    public function setPermiso(Request $request)
    {
        $rol = Rol::with('Permisos')->find($request->rol_id);
        $Permisos = $rol->Permisos;
        $PermisosReq = $request->permisos;
        // if (isset($request->name)) {
        //   if($request->name === "all-access" || $request->name === "no-access"){
        //     $rol->descripcion = ["special" => $request->name];
        //     $rol->save();
        //   }
        //   $model = Rol::with('Permisos')->find($request->rol_id);
        //   return response()->json($model);
        // } else {
        //   $rol->descripcion = ["special" => null];
        //   $rol->save();
        // }

        foreach ($Permisos as $p) {
            $assign = false;
            foreach ($PermisosReq as $pr) {
                if ($p->permiso_id === $pr['id']) {
                    $assign = true;
                    break;
                }
            }
            if(!$assign) $rol->Permisos()->detach($p->permiso_id);
        }
        foreach ($PermisosReq as $pr) {
            $assign = false;
            foreach ($Permisos as $p) {
                if ($pr['id'] === $p->permiso_id) {
                    $assign = true;
                    break;
                }
            }
            if(!$assign) $rol->Permisos()->attach($pr['id']);
        }
        $model = Rol::with('Permisos')->find($request->rol_id);
        return response()->json($model);
    }

    public function listPermisos(Request $request)
    {
        return $this->show($request);
    }
}

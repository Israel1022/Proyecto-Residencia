<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Contacto;
use App\Models\Empresa\Empleado;
use App\Models\Empresa\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function show(Request $request)
    {
        $empleados = Empleado::query()->with('Departamento');

        $empleados->when($request->id === "ENABLE", function ($q) {
            return $q->where("activo", "si");
        });

        $empleados->when($request->id === "DISABLED", function ($q) {
            return $q->where("activo", "no");
        });

        $empleados->when($request->id > 0, function ($q) use ($request) {
            return $q->find($request->id);
        });

        
        $empresa_id = \Auth::user()->Empleado->Departamento->empresa_id;
        $isAll =  \Auth::user()->can("Access.system-All");
        // return $isAll;
        $empleados->when(!$isAll, function ($q) use ($empresa_id) {
            return $q->whereHas('Departamento', function ($q) use ($empresa_id) {
                $q->where('empresa_id', $empresa_id);
            });
        });

        if ($request->id > 0) {
            return response($empleados->first());
        }
        return response($empleados->get());
    }

    public function showDirecciones(Request $request)
    {
        $contacto = Contacto::query()->where("tipo", "DIRECCION");
        $contacto->when($request->id > 0, function ($q) use ($request) {
            return $q->where("folio", $request->id);
        });

        return response($contacto->get());
    }

    public function createOrUpdate(Request $request)
    {
        try {
            $empleado = isset($request->id) ? Empleado::where('id', $request->id)->first() : new Empleado();

            $empleado->departamento_id = $request->departamento_id;
            $empleado->cve_empleado = $request->cve_empleado;
            $empleado->nombres = $request->nombres;
            $empleado->apellidos = $request->apellidos;
            $empleado->folio = '';

            $empleado->save();
            $model = Empleado::with('Departamento')->find($empleado->id);

            return response()->json($model);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $this->deleteObject(Empleado::class,$id);
        return response()->json(["id" => $id ]);
    }

    public function activateOrDesactivate ($id)
    {
        $this->enableOrDisableObject(Empleado::class,$id);
        $empleado = Empleado::with('Departamento')->find($id);
        return response()->json($empleado);
    }

    public function findEmployeByNames($names)
    {
        // $empleados = $this->listObject('App\Models\Empresa\Empleado');
        $empleados = DB::select("SELECT emp.*, CONCAT(emp.nombres,' ',emp.apellidos) as NombreCompleto FROM sts_emp_empleados emp INNER JOIN sts_emp_usuarios us ON emp.id = us.empleado_id 
            WHERE ( CONCAT( emp.nombres,' ',emp.apellidos) LIKE '%" . $names . "%' OR emp.cve_empleado LIKE '%" . $names . "%') AND emp.activo = 'si' ");
        /* $empleados = DB::select("SELECT * FROM sts_emp_empleados WHERE CONCAT( nombres,' ',apellidos) LIKE '%".$names."%' OR cve_empleado LIKE '%".$names."'"); */
        return response()->json($empleados);
    }

    public function find($names) {
      try {
        $isAll = \Auth::user()->can('Access.system-All');
        $empresa_id = \Auth::user()->Empleado->Departamento->empresa_id;
        if (!$isAll) {
            $ids_deptos = "";
            $departamentos = \Auth::user()->DepartamentosAsignados;
            foreach ($departamentos as $depto) $ids_deptos .= ($ids_deptos == "") ? "".$depto->id : ",".$depto->id;
            $query = " e.departamento_id IN ($ids_deptos) AND ";
        }
        $query = " d.empresa_id = $empresa_id AND ";

        $empleados = DB::select("SELECT e.id,e.cve_empleado, CONCAT_WS(' ',TRIM(e.nombres),TRIM(e.apellidos)) AS NombreCompleto, d.nombre FROM sts_emp_empleados e
        INNER JOIN sts_emp_departamentos d ON d.id = e.departamento_id
        WHERE $query (CONCAT_WS(' ',TRIM(e.nombres),TRIM(e.apellidos)) LIKE '%$names%' OR e.cve_empleado LIKE '%$names%') AND e.activo = 'si' ");

        return response()->json($empleados);
      } catch (Exception $e) {
        return response(['message' => $e->getMessage()]);
      }
    }

    public function findUserEmploye($names)
    {
        $query = "";
        $empresa_id = \Auth::user()->Empleado->Departamento->empresa_id;
        /*
        $isAll = \Auth::user()->can('Access.system-All');
        if (!$isAll) {
            $ids_deptos = "";
            $departamentos = \Auth::user()->DepartamentosAsignados;
            foreach ($departamentos as $depto) $ids_deptos .= ($ids_deptos == "") ? "".$depto->id : ",".$depto->id;
            $query = " e.departamento_id IN ($ids_deptos) AND ";
        }
        */
        $query .= " d.empresa_id = $empresa_id AND "; 
        $usuarios = DB::select("SELECT e.id,e.cve_empleado, CONCAT_WS(' ',TRIM(e.nombres),TRIM(e.apellidos)) AS NombreCompleto, d.nombre FROM sts_emp_usuarios u
        INNER JOIN sts_emp_empleados e ON e.id = u.empleado_id
        INNER JOIN sts_emp_departamentos d ON d.id = e.departamento_id
        WHERE $query (CONCAT_WS(' ',TRIM(e.nombres),TRIM(e.apellidos)) LIKE '%$names%' OR u.usuario LIKE '%$names%')");
        return response()->json($usuarios);
    }
}

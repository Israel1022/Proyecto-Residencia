<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Departamento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartamentoController extends Controller
{
    public function show(Request $request)
    {
        $empresa_id = \Auth::user()->Empleado->Departamento->empresa_id;
        $departamentos = Departamento::with(['Father', 'Empresa'])
            ->where('empresa_id',$empresa_id)
            // ->where('activo','si')
            ->get();

        return response()->json($departamentos);
    }

    public function createOrUpdate(Request $request)
    {
        try {
            $this->validations($request,[
                'nombre' => [
                    'required',
                    Rule::unique('sts_emp_departamentos')->ignore($request['id']),
                ]
            ]);

            $departamento = isset($request->id) ? Departamento::where('id', $request->id)->first() : new Departamento();

            $departamento->nombre = $request->nombre;
            $departamento->empresa_id = $request->empresa_id;
            $departamento->save();

            $model = Departamento::with('Empresa')->find($departamento->id);

            return response()->json($model);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $this->deleteObject(Departamento::class,$id);
        return response()->json(["id" =>$id ]);
    }

    public function activateOrDesactivate ($id)
    {
        return $this->enableOrDisableObject(Departamento::class,$id);
    }

    public function findEmployeByNames($names)
    {
        // $empleados = $this->listObject('App\Models\Empresa\Empleado');
        $empleados = DB::select("SELECT emp.*, CONCAT(emp.nombres,' ',emp.apellidos) as NombreCompleto FROM sts_emp_empleados emp INNER JOIN sts_emp_usuarios us ON emp.id = us.empleado_id WHERE CONCAT( emp.nombres,' ',emp.apellidos) LIKE '%" . $names . "%' OR emp.cve_empleado LIKE '%" . $names . "%' ");
        /* $empleados = DB::select("SELECT * FROM sts_emp_empleados WHERE CONCAT( nombres,' ',apellidos) LIKE '%".$names."%' OR cve_empleado LIKE '%".$names."'"); */
        return response()->json($empleados);
    }

    public function asignados()
    {
        $isAll = \Auth::user()->can('Access.system-All');
        if (!$isAll) {
            $departamentos = \Auth::user()->DepartamentosAsignados;
            return response()->json($departamentos);
        }
        $empresa_id = \Auth::user()->Empleado->Departamento->empresa_id;
        $departamentos = Departamento::where('empresa_id',$empresa_id)->where('activo','si')->get();
        return response()->json($departamentos);
    }

    public function findDepartamentosChildren () {
        $departamentos = Departamento::where('padre_id',0)->where('activo','si')->get();
        foreach ($departamentos as $value) {
            $children = Departamento::where('padre_id',$value->id)->where('activo','si')->get();
            foreach ($children as $valueP) {
                $childrenp = Departamento::where('padre_id',$valueP->id)->where('activo','si')->get();
                $valueP['children'] = $childrenp;
            }
            $value['children'] = $children;
        }
        return response()->json($departamentos);
    }

    public function findDeptoByNames ($names){
        $departamentos = Departamento::where('nombre','like',"%$names%")->get();
        return response()->json($departamentos);
    }


    public function store(Request $request)
    {
        try {
            $departamento = isset($request->id)? Departamento::find($request->id) : new Departamento();

            $departamento->nombre = $request->nombre;
            $departamento->empresa_id = $request->empresa_id;
            $departamento->save();

            return response(Departamento::find($departamento->id));
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()]);
        }
    }
}

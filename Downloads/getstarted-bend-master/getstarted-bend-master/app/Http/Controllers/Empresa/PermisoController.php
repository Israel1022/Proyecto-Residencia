<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        $permiso = ($request->id)? Permiso::find($request->id) : new Permiso;

        $permiso->nombre = $request->nombre;
        $permiso->slug =$request->slug;
        $permiso->descripcion = $request->descripcion ? $request->descripcion : '';

        $permiso->save();

        return response(Permiso::find($permiso->id));
    }

    public function show(Request $request)
    {
        $permiso = Permiso::query();

        $permiso->when($request->name === "ARBOL", function($q) {
            return $q->with('Children.Children.Children')->where("activo","si")->where("padre_id",0);
        });
        
        $permiso->when($request->id === "ENABLE", function($q){
            return $q->where("activo","si");
        });

        $permiso->when($request->id === "DISABLED", function($q){
            return $q->where("activo","no");
        });

        $permiso->when($request->id > 0, function($q) use($request){
            return $q->find($request->id);
        });

        return response($permiso->get());
    }

    public function destroy(Request $request)
    {
       return $this->enableOrDisableObject(Permiso::class,$request->id);
    }
}

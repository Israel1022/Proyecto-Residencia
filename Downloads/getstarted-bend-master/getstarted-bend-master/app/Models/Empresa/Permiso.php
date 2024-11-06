<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table="sts_emp_permisos";
    protected $appends = ['name'];
    protected $hidden = ['created_at','updated_at'];

    public function SubPermisos(){
        return $this->hasMany(Permiso::class,'padre_id','id');
    }

    public function Children(){
        return $this->hasMany(Permiso::class,'padre_id','id');
    }

    public function getNameAttribute()
    {
        return $this->attributes['nombre'];
    }
}

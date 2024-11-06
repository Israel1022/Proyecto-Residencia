<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table="sts_emp_roles";

    protected $casts = ['descripcion' => 'array'];
    protected $hidden = ['created_at','updated_at'];

    public function Permisos() {
        return $this->belongsToMany(Permiso::class,'sts_emp_permiso_rol','rol_id','permiso_id')->withTimestamps();
    }
}

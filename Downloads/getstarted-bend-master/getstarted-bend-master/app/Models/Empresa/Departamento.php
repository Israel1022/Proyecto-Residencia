<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = "sts_emp_departamentos";
    //

    public function Father()
    {
        return $this->hasOne('App\Models\Empresa\Departamento', 'padre_id', 'id');
    }

    public function Empresa()
    {
        return $this->hasOne('App\Models\Empresa\Empresa', 'id', 'empresa_id');
    }
}

<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $table="sts_emp_contactos";
    //
    protected $appends = ['Direccion'];

    public function getDireccionAttribute() {
        if ($this->attributes['tipo'] == "DIRECCION") {
            return $this->attributes['contacto'];
        }
        return '';
    }
}

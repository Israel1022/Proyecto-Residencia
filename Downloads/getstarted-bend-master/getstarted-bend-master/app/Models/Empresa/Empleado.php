<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $appends = ['NombreCompleto','contacto'];
    protected $fillable = [
        'departamento_id',
        'cve_empleado',
        'nombres',
        'apellidos',
        'folio',
        'id'
    ];
    protected $hidden = ['created_at','updated_at'];
    protected $table="sts_emp_empleados";
    //
    public function Departamento() {
        return $this->belongsTo(Departamento::class,'departamento_id');
    }

    public function getNombreCompletoAttribute(){
        return $this->attributes['nombres'].' '.$this->attributes['apellidos'];
    }

    public function getContactoAttribute(){
        $telefono = Contacto::where('tipo','TELEFONO')->where('folio',$this->attributes['id'])->first();
        $correo = Contacto::where('tipo','CORREO')->where('folio',$this->attributes['id'])->first();
        return [
          'telefono' => isset($telefono) ? $telefono->contacto : '',
          'correo' =>  isset($correo) ? $correo->contacto : ''
        ];
    }
}

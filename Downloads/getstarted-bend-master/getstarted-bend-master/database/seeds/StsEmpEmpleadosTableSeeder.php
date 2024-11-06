<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpEmpleadosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_empleados')->delete();
        
        \DB::table('sts_emp_empleados')->insert(array (
            0 => 
            array (
                'id' => 1,
                'departamento_id' => 1,
                'cve_empleado' => '0001',
                'nombres' => 'ROBERTO E.',
                'apellidos' => 'HERNANDEZ D.',
                'activo' => 'si',
                'folio' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
           
        ));        
        
    }
}
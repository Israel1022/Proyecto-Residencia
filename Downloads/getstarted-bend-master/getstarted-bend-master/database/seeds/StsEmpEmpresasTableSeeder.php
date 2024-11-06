<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpEmpresasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_empresas')->delete();
        
        \DB::table('sts_emp_empresas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'NOMBRE DE LA EMPRESA',
                'activo' => 'si',
                'created_at' => '2020-12-18 17:35:54',
                'updated_at' => '2020-12-18 17:35:54',
            ),
        ));
        
        
    }
}
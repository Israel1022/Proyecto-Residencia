<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpRolUsuarioTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_rol_usuario')->delete();
        
        \DB::table('sts_emp_rol_usuario')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'rol_id' => 1,
                'activo' => 'si',
                'created_at' => '2021-05-28 16:00:41',
                'updated_at' => '2021-05-28 16:00:41',
            ),
        ));
        
        
    }
}
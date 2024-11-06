<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_roles')->delete();
        
        \DB::table('sts_emp_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Administrador',
                'slug' => 'admin',
                'descripcion' => '{"special":"all-access"}',
                'activo' => 'si',
                'created_at' => '2021-05-28 15:59:45',
                'updated_at' => '2021-08-10 08:23:15',
            ),
        ));
        
        
    }
}
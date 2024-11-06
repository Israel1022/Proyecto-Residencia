<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpUsuariosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_usuarios')->delete();
        
        \DB::table('sts_emp_usuarios')->insert(array (
            0 => 
            array (
                'id' => 1,
                'empleado_id' => 1,
                'usuario' => 'admin',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'email_verified_at' => '2021-05-28 10:58:52',
                'remember_token' => NULL,
                'api_token' => NULL,
                'activo' => 'si',
                'created_at' => '2021-05-28 15:58:52',
                'updated_at' => '2021-05-28 15:58:52',
            ),
        ));
        
        
    }
}
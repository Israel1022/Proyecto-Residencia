<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpConfiguracionesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_configuraciones')->delete();
        
        \DB::table('sts_emp_configuraciones')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'LOGOS_ICONS_SYSTEMS',
                'descripcion' => '{"icon": "./favicon_old.ico", "logo": "logo_login.png", "logo_reporte1": {"h": 30, "w": 35, "x": 0, "y": 6, "image": "yucatan_logo.png"}}',
                'activo' => 'si',
                'created_at' => '2021-11-27 01:38:32',
                'updated_at' => '2021-11-27 01:38:32',
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpFoliadoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_foliadores')->delete();
        
        \DB::table('sts_emp_foliadores')->insert(array (
            0 => 
            array (
                'id' => 1,
                'activo' => 'si',
                'exprecion' => '{"replace":["{identity}","{year}","{folio}"],"expresion": "{identity}-{year}-{folio}"}',
                'folio' => 'EJ',
                'ultimofolio' => '0',
                'tipo_folio' => 'SECUENCIAL',
                'empresa_id' => 1,
                'created_at' => '2021-01-30 08:40:08',
                'updated_at' => '2021-08-13 12:03:27',
            ),
        ));
        
        
    }
}
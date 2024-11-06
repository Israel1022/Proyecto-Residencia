<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpDepartamentosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_departamentos')->delete();
        \DB::table('sts_emp_departamentos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'padre_id' => 0,
                'nombre' => 'Informática',
                'empresa_id' => 1,
                'activo' => 'si',
                'created_at' => '2019-01-17 06:00:00',
                'updated_at' => '2019-01-17 06:00:00',
            ),
            array (
                'id' => 2,
                'padre_id' => 0,
                'nombre' => '{
                    Tabla_tipos_datos:{
                        Nombre: Campo Selector-DB Col-12,
                        defaultl: { "field": "selectDataServer", "nameid": "marca_id", "url": "catalogo/marcas", "objectname": "marca", "value": "id", "rules": true, "cols": 12 }
                    },
                    Tabla_caracteristicas: {
                        Nombre: Marca
                        Identificador: marca
                        Tipo_dato_id:{ desc: es del catalogo de tipo de datos}
                    }
                }
                ',
                'empresa_id' => 1,
                'activo' => 'si',
                'created_at' => '2019-01-17 06:00:00',
                'updated_at' => '2019-01-17 06:00:00',
            ),
            
        ));
    }
}
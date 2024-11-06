<?php

use Illuminate\Database\Seeder;
use Database\Seeders\StsEmpEmpresasTableSeeder;
use Database\Seeders\StsEmpDepartamentosTableSeeder;
use Database\Seeders\StsEmpEmpleadosTableSeeder;
use Database\Seeders\StsEmpFoliadoresTableSeeder;
use Database\Seeders\StsEmpRolesTableSeeder;
use Database\Seeders\StsEmpRolUsuarioTableSeeder;
use Database\Seeders\StsEmpUsuariosTableSeeder;
use Database\Seeders\StsEmpPermisosTableSeeder;
use Database\Seeders\StsEmpConfiguracionesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StsEmpEmpresasTableSeeder::class);
        $this->call(StsEmpDepartamentosTableSeeder::class);
        $this->call(StsEmpEmpleadosTableSeeder::class);
        $this->call(StsEmpFoliadoresTableSeeder::class);
        $this->call(StsEmpUsuariosTableSeeder::class);
        $this->call(StsEmpRolesTableSeeder::class);
        $this->call(StsEmpRolUsuarioTableSeeder::class);
        $this->call(StsEmpPermisosTableSeeder::class);
        $this->call(StsEmpConfiguracionesTableSeeder::class);
    }
}

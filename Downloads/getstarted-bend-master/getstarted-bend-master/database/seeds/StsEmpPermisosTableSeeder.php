<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StsEmpPermisosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sts_emp_permisos')->delete();
        
        \DB::table('sts_emp_permisos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'padre_id' => 0,
                'nombre' => 'Configuracones',
                'slug' => 'configuraciones',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            1 => 
            array (
                'id' => 2,
                'padre_id' => 0,
                'nombre' => 'Accesos',
                'slug' => 'configuraciones.accesos',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            2 => 
            array (
                'id' => 3,
                'padre_id' => 2,
                'nombre' => 'Usuarios',
                'slug' => 'empresa.usuarios.show',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            3 => 
            array (
                'id' => 4,
                'padre_id' => 3,
                'nombre' => 'Usuarios Crear y Editar',
                'slug' => 'empresa.usuarios.store',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            4 => 
            array (
                'id' => 5,
                'padre_id' => 3,
                'nombre' => 'Usuarios Cambiar Contraseña',
                'slug' => 'empresa.usuarios.change-password',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            5 => 
            array (
                'id' => 6,
                'padre_id' => 3,
                'nombre' => 'Usuarios Actualizar',
                'slug' => 'empresa.usuarios.update',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            6 => 
            array (
                'id' => 7,
                'padre_id' => 3,
                'nombre' => 'Usuarios Asignar Departamento',
                'slug' => 'empresa.usuarios.set-depto',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            7 => 
            array (
                'id' => 8,
                'padre_id' => 3,
                'nombre' => 'Usuarios Asignar Rol',
                'slug' => 'empresa.usuarios.set-rol',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            8 => 
            array (
                'id' => 9,
                'padre_id' => 2,
                'nombre' => 'Roles',
                'slug' => 'empresa.roles.show',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            9 => 
            array (
                'id' => 10,
                'padre_id' => 9,
                'nombre' => 'Roles Crear y Editar',
                'slug' => 'empresa.roles.create-update',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            10 => 
            array (
                'id' => 11,
                'padre_id' => 9,
                'nombre' => 'Roles Eliminar',
                'slug' => 'empresa.roles.destroy',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            11 => 
            array (
                'id' => 12,
                'padre_id' => 9,
                'nombre' => 'Roles Asignar Permiso',
                'slug' => 'empresa.roles.set-permiso',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            12 => 
            array (
                'id' => 13,
                'padre_id' => 0,
                'nombre' => 'Permisos',
                'slug' => 'roles.permisos.show',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            13 => 
            array (
                'id' => 14,
                'padre_id' => 13,
                'nombre' => 'Permisos Crear y Editar',
                'slug' => 'roles.permisos.create-update',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            14 => 
            array (
                'id' => 15,
                'padre_id' => 13,
                'nombre' => 'Permisos Eliminar',
                'slug' => 'roles.permisos.destroy',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            15 => 
            array (
                'id' => 16,
                'padre_id' => 0,
                'nombre' => 'Empleados',
                'slug' => 'empresa.empleados.show',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            16 => 
            array (
                'id' => 17,
                'padre_id' => 16,
                'nombre' => 'Empleados Crear y Editar',
                'slug' => 'empresa.empleados.create-update',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            17 => 
            array (
                'id' => 18,
                'padre_id' => 16,
                'nombre' => 'Empleados Activar y Desactivar',
                'slug' => 'empresa.empleados.up-down',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            18 => 
            array (
                'id' => 19,
                'padre_id' => 16,
                'nombre' => 'Empleados Eliminar',
                'slug' => 'empresa.empleados.delete',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            19 => 
            array (
                'id' => 20,
                'padre_id' => 0,
                'nombre' => 'Acceso total',
                'slug' => 'Access.system-All',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-01-26 16:57:47',
                'updated_at' => '2024-01-26 16:57:47',
            ),
            20 => 
            array (
                'id' => 21,
                'padre_id' => 0,
                'nombre' => 'Departamentos',
                'slug' => 'empresa.departamentos.show',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            21 => 
            array (
                'id' => 22,
                'padre_id' => 21,
                'nombre' => 'Departamentos Crear y Editar',
                'slug' => 'empresa.departamentos.create-update',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            22 => 
            array (
                'id' => 23,
                'padre_id' => 21,
                'nombre' => 'Departamentos Activar y Desactivar',
                'slug' => 'empresa.departamentos.up-down',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            23 => 
            array (
                'id' => 24,
                'padre_id' => 21,
                'nombre' => 'Departamentos Eliminar',
                'slug' => 'empresa.departamentos.delete',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            24 => 
            array (
                'id' => 25,
                'padre_id' => 21,
                'nombre' => 'Departamentos Listado Arbol',
                'slug' => 'empresa.departamentos.children',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            25 => 
            array (
                'id' => 26,
                'padre_id' => 21,
                'nombre' => 'Buscador Departamentos',
                'slug' => 'empresa.departamentos.find',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            26 => 
            array (
                'id' => 27,
                'padre_id' => 21,
                'nombre' => 'Asignacion de Departamentos',
                'slug' => 'empresa.departamentos.show-assig',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            27 => 
            array (
                'id' => 28,
                'padre_id' => 16,
                'nombre' => 'Buscador de empleados',
                'slug' => 'empresa.empleados.find',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            28 => 
            array (
                'id' => 29,
                'padre_id' => 99,
                'nombre' => 'Buscador de Direcciones',
                'slug' => 'empleados.direcciones.show',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
            29 => 
            array (
                'id' => 30,
                'padre_id' => 16,
                'nombre' => 'Buscador de usuarios Empleado',
                'slug' => 'empresa.emp-by-name-find',
                'descripcion' => '',
                'activo' => 'si',
                'created_at' => '2024-02-02 16:57:47',
                'updated_at' => '2024-02-02 16:57:47',
            ),
        ));
        
        
    }
}
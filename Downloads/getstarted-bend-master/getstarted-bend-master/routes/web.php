<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', ['as' => 'lumen.version', function () use ($router) {
    return $router->app->version();
}]);

$router->post('app/rutas',  ['as' => 'app.rutas', function () use ($router) {
    return collect($router->getRoutes())->pluck('uri', 'action.as');
}]);

/** * Modulo de Empresa lOGIN * **/
$router->post('empresas/usuarios/login', ['as' => 'usuario.login', 'uses' => 'Empresa\UsuarioController@login']);
$router->get('empresas/logos', ['as' => 'empresa.usuario.logos', 'uses' => 'Empresa\EmpresaController@logos']);

$router->get('migrate', function () {
    return Illuminate\Support\Facades\Artisan::call('migrate',['--force' => true]);
});

$router->get('seed/{class}', function ($class) {
    return Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => $class, '--force' => true]);
});

$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->get('app/key', ['as' => 'app.key', function () {
        return Illuminate\Support\Str::random(32);
    }]);
    $router->post('app/permisos',  ['as' => 'app.permisos', function () use ($router) {
        $lista = collect($router->getRoutes())->pluck('action.middleware', 'action.as');
        $permisos = [];
        $lista->each(function ($item, $key) use (&$permisos) {
            if (is_array($item) && count($item) == 2)
                $permisos[$key] = explode(':', $item[1])[1];
        });

        return $permisos;
    }]);
    $router->post('empresas/usuarios/logout', ['as' => 'usuario.logout', 'uses' => 'Empresa\UsuarioController@logout']);
    
    /** Modulo de Empresa **/
    $router->group(['prefix' => 'empresa', 'namespace' => 'Empresa'], function () use ($router) {
        $router->group(['prefix' => 'empleados'], function () use ($router) {
            $router->get('/', ['as' => 'empresa.empleados.show', 'middleware' => 'permiso:empresa.empleados.show', 'uses' => 'EmpleadoController@show']);
            $router->get('/direcciones/show', ['as' => 'empleados.direcciones.show', 'middleware' => 'permiso:empleados.direcciones.show', 'uses' => 'EmpleadoController@showDirecciones']);
            
            $router->get('find/{names}', ['as' => 'empresa.empleados.find', 'middleware' => 'permiso:empresa.empleados.find', 'uses' => 'EmpleadoController@find']);
            $router->get('usuario/find/{names}', ['as' => 'empresa.emp-by-name-find', 'middleware' => 'permiso:empresa.emp-by-name-find', 'uses' => 'EmpleadoController@findUserEmploye']);

            $router->post('/', ['as' => 'empresa.empleados.create-update', 'middleware' => 'permiso:empresa.empleados.create-update', 'uses' => 'EmpleadoController@createOrUpdate']);
            $router->put('/{id}', ['as' => 'empresa.empleados.up-down', 'middleware' => 'permiso:empresa.empleados.up-down', 'uses' => 'EmpleadoController@activateOrDesactivate']);
            $router->delete('/{id}', ['as' => 'empresa.empleados.delete', 'middleware' => 'permiso:empresa.empleados.delete', 'uses' => 'EmpleadoController@delete']);
        });
        $router->group(['prefix' => 'departamentos'], function () use ($router) {
            $router->get('/', ['as' => 'empresa.departamentos.show', 'middleware' => 'permiso:empresa.departamentos.show', 'uses' => 'DepartamentoController@show']);
            $router->post('/', ['as' => 'empresa.departamentos.create-update', 'middleware' => 'permiso:empresa.departamentos.create-update', 'uses' => 'DepartamentoController@createOrUpdate']);
            $router->put('/{id}', ['as' => 'empresa.departamentos.up-down', 'middleware' => 'permiso:empresa.departamentos.up-down', 'uses' => 'DepartamentoController@activateOrDesactivate']);
            $router->delete('/{id}', ['as' => 'empresa.departamentos.delete', 'middleware' => 'permiso:empresa.departamentos.delete', 'uses' => 'DepartamentoController@delete']);

            $router->get('/children', ['as' => 'empresa.departamentos.children', 'middleware' => 'permiso:empresa.departamentos.children', 'uses' => 'DepartamentoController@findDepartamentosChildren']);
            $router->get('find/{names}', ['as' => 'empresa.departamentos.find', 'middleware' => 'permiso:empresa.departamentos.find', 'uses' => 'DepartamentoController@findDeptoByNames']);
            $router->get('asignados', ['as' => 'empresa.departamentos.show-assig', 'middleware' => 'permiso:empresa.departamentos.show-assig', 'uses' => 'DepartamentoController@asignados']);
        });
        $router->group(['prefix' => 'usuarios'], function () use ($router) {
            $router->get('/', ['as' => 'empresa.usuarios.show', 'middleware' => 'permiso:empresa.usuarios.show', 'uses' => 'UsuarioController@show']);
            $router->post('/', ['as' => 'empresa.usuarios.store', 'middleware' => 'permiso:empresa.usuarios.store', 'uses' => 'UsuarioController@store']);
            $router->post('change-password', ['as' => 'empresa.usuarios.change-password', 'middleware' => 'permiso:empresa.usuarios.change-password', 'uses' => 'UsuarioController@changePassword']);

            $router->put('/', ['as' => 'empresa.usuarios.update', 'middleware' => 'permiso:empresa.usuarios.update', 'uses' => 'UsuarioController@store']);

            $router->post('set-depto', ['as' => 'empresa.usuarios.set-depto', 'middleware' => 'permiso:empresa.usuarios.set-depto', 'uses' => 'UsuarioController@setDepartamento']);
            $router->post('set-rol', ['as' => 'empresa.usuarios.set-rol', 'middleware' => 'permiso:empresa.usuarios.set-rol', 'uses' => 'UsuarioController@rol']);
        });
        $router->group(['prefix' => 'roles'], function () use ($router) {
            $router->get('', ['as' => 'empresa.roles.show', 'middleware' => 'permiso:empresa.roles.show', 'uses' => 'RolController@show']);
            $router->post('store', ['as' => 'empresa.roles.create-update', 'middleware' => 'permiso:empresa.roles.create-update', 'uses' => 'RolController@createOrUpdate']);
            $router->delete('/{id}', ['as' => 'empresa.roles.destroy', 'middleware' => 'permiso:accesos.roles.destroy', 'uses' => 'RolController@destroy']);
            $router->post('set-permiso', ['as' => 'empresa.roles.set-permiso', 'middleware' => 'permiso:empresa.roles.set-permiso', 'uses' => 'RolController@setPermiso']);
            $router->group(['prefix' => 'permisos'], function () use ($router) {
                $router->get('/', ['as' => 'roles.permisos.show', 'middleware' => 'permiso:roles.permisos.show', 'uses' => 'PermisoController@show']);
                $router->post('/store', ['as' => 'roles.permisos.create-update', 'middleware' => 'permiso:roles.permisos.create-update', 'uses' => 'PermisoController@createOrUpdate']);
                $router->post('/destroy', ['as' => 'roles.permisos.destroy', 'middleware' => 'permiso:roles.permisos.destroy', 'uses' => 'PermisoController@destroy']);
            });
        });
        $router->group(['prefix' => 'tipo-actividades'], function () use ($router) {
            $router->get('/show', ['as' => 'empresa.tipo-actividad.show', 'middleware' => 'permiso:empresa.tipo-actividad.show', 'uses' => 'TipoActividadController@list']);
            $router->get('/show-solicitudes', ['as' => 'empresa.tipo-actividad.show-solicitudes', 'middleware' => 'permiso:empresa.tipo-actividad.show-solicitudes', 'uses' => 'TipoActividadController@listSolicitudes']);
        });
    });
    $router->group(['prefix' => 'procesos', 'namespace' => 'Procesos'], function () use ($router) {
        $router->group(['prefix' => 'movimientos'], function () use ($router) {
            $router->get('/', ['as' => 'procesos.movimientos.show', 'middleware' => 'permiso:procesos.movimientos.show', 'uses' => 'MovimientosController@list']);
        });
        $router->group(['prefix' => 'tipo-proceso'], function () use ($router) {
            $router->get('/', ['as' => 'tipo-proceso.procesos-list', 'middleware' => 'permiso:tipo-proceso.procesos-list', 'uses' => 'Procesos\TipoProcesoController@list']);
            $router->post('/', ['as' => 'tipo-proceso.create-update', 'middleware' => 'permiso:tipo-proceso.create-update', 'uses' => 'Procesos\TipoProcesoController@createOrUpdate']);
            $router->put('/{id}', ['as' => 'tp.up-down', 'middleware' => 'permiso:tipo-proceso.up-down', 'uses' => 'Procesos\TipoProcesoController@activateOrDesactivate']);
            $router->delete('/{id}', ['as' => 'tp.delete', 'middleware' => 'permiso:tipo-proceso.delete', 'uses' => 'Procesos\TipoProcesoController@delete']);
        });
        $router->group(['prefix' => 'estatus'], function () use ($router) {
            $router->get('/', ['as' => 'estatus.list', 'middleware' => 'permiso:estatus.list', 'uses' => 'Procesos\EstatusController@list']);
            $router->post('/', ['as' => 'estatus.create-update', 'middleware' => 'permiso:estatus.create-update', 'uses' => 'Procesos\EstatusController@createOrUpdate']);
            $router->put('/{id}', ['as' => 'estatus.up-down', 'middleware' => 'permiso:estatus.up-down', 'uses' => 'Procesos\EstatusController@activateOrDesactivate']);
            $router->delete('/{id}', ['as' => 'estatus.delete', 'middleware' => 'permiso:estatus.delete', 'uses' => 'Procesos\EstatusController@delete']);
        });
        $router->group(['prefix' => 'proceso'], function () use ($router) {
            $router->post('/', ['as' => 'proceso.notificacion', 'middleware' => 'permiso:proceso.notificacion', 'uses' => 'Procesos\TipoProcesoController@ProcesoNotificacion']);
        });
    });
    $router->group(['prefix' => 'catalogo', 'namespace' => 'Catalogos'], function () use ($router) {
        // $router->group(['prefix' => 'movimientos'], function () use ($router) {});
    });

    
});


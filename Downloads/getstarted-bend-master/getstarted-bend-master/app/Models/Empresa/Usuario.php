<?php

namespace App\Models\Empresa;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Support\Facades\DB;
//this is new
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject 
{
    use Authenticatable, Authorizable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "sts_emp_usuarios";

    protected $fillable = ['usuario'];
    protected $appends = ['RolesAsignados','departamentosAsignadosIds'];
    protected $casts = ['descripcion' => 'array'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'api_token'];

    public function Empleado()
    {
        return $this->belongsTo('App\Models\Empresa\Empleado', 'empleado_id');
    }

    public function Roles()
    {
        return $this->belongsToMany('App\Models\Empresa\Rol', 'sts_emp_rol_usuario', 'user_id', 'rol_id');
    }

    public function DepartamentosAsignados()
    {
        return $this->belongsToMany('App\Models\Empresa\Departamento', 'sts_emp_departamento_usuario', 'user_id', 'departamento_id');
    }

    public function AauthAcessToken()
    {
        return $this->hasMany('App\Models\Empresa\OauthAccessToken', 'user_id', 'id');
    }
    public function findForPassport($username)
    {
        return $user = (new Usuario)->where('usuario', $username)->first();
    }

    public function getRolesAsignadosAttribute()
    {
        $rolesname = '';
        $roles = DB::select("SELECT r.nombre FROM sts_emp_rol_usuario ru 
            INNER JOIN sts_emp_usuarios u ON ru.user_id = u.id
            INNER JOIN sts_emp_roles r ON ru.rol_id = r.id
            WHERE ru.user_id =" . $this->attributes['id']);
        if (isset($roles)) {
            foreach ($roles as $role) {
                $rolesname .= ($rolesname == '') ? $role->nombre : ',' . $role->nombre;
            }
        }
        return $rolesname;
    }

    public function getDepartamentosAsignadosIdsAttribute()
    {
        $deptosname = [];
        $deptos = DB::select("SELECT d.id FROM sts_emp_departamento_usuario du 
            INNER JOIN sts_emp_usuarios u ON du.user_id = u.id
            INNER JOIN sts_emp_departamentos d ON du.departamento_id = d.id
            WHERE du.user_id =" . $this->attributes['id']);
        if (isset($deptos)) {
            foreach ($deptos as $depto) { array_push($deptosname,$depto->id); }
        }
        return $deptosname;
    }


    /**
     * Checks if the user has the given role.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isRole($slug)
    {
        $slug = strtolower($slug);

        foreach ($this->roles as $role) {
            if ($role['slug'] == $slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has the given permission.
     *
     * @param string $permission
     * @param array  $arguments
     *
     * @return bool
     */
    public function can($permission, $arguments = [])
    {
        //\Log::debug(dd($permission));
        foreach ($this->roles as $role) {
            $descripcion = $role['descripcion'];
            // \Log::debug($descripcion);
            if (isset($descripcion['special'])) {
                if ($descripcion['special'] === 'no-access') {
                    return false;
                }
                if ($descripcion['special'] === 'all-access') {
                    return true;
                }
            }
        }
        return $this->hasAllPermissions($permission, $this->getPermissions());
    }

    /**
     * Check if the or all requested permissions are satisfied
     *
     * @param mixed $permission
     * @param array $permissions
     *
     * @return bool
     */
    protected function hasAllPermissions($permission, array $permissions)
    {
        if (is_array($permission)) {
            $permissionCount   = count($permission);
            $intersection      = array_intersect($permissions, $permission);
            $intersectionCount = count($intersection);

            return ($permissionCount == $intersectionCount) ? true : false;
        } else {
            // \Log::debug(print_r($permission,true));
            return in_array($permission, $permissions);
        }
    }

    /**
     * Wrapper for caching the permission slugs
     *
     * @return array
     */
    public function getPermissions()
    {
        $permissions = [];

        foreach ($this->roles as $role) {
            $permissions = $role['permisos'];
        }

        // \Log::debug(print_r($permissions->toArray(),true));
        // print_r($permissions->toArray());
        // exit;

        return empty($permissions) === false ? $this->array_pluck($permissions->toArray(), 'slug') : [];
    }

    function array_pluck($array, $key)
    {
        return array_map(function ($v) use ($key) {
            return is_object($v) ? $v->$key : $v[$key];
        }, $array);
    }
}

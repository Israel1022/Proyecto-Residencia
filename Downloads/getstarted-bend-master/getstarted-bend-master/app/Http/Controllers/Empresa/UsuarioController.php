<?php

namespace App\Http\Controllers\Empresa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresa\Usuario;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        try {

            $this->validate($request, [
                'usuario' => 'required|string',
                'password' => 'required|string',
            ]);

            $data =  $request->json()->all();

            if (isset($data['captcha'])) {

                $secred = config('ini.captcha_secret');
                $client = new Client([
                    'verify' => false
                ]);
                $response = $client->post('https://google.com/recaptcha/api/siteverify', [
                    'form_params' => [
                        'secret' => $secred,
                        'response' => $data['captcha']
                    ]
                ]);

                $responseCaptcha = json_decode($response->getBody())->success;
                if ($responseCaptcha) {
                    $credentials = $request->only(['usuario', 'password']);

                    if (! $token = Auth::attempt($credentials)) {
                        return response()->json(['message' => 'Usuario o contraseña incorrecto']);
                    }

                    return $this->respondWithToken($token);
                }
                return $response->getBody();
            } else {
                return response()->json(['message' => 'Captcha no debe estar vacía']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        
        return response()->json(['result' => 'Session terminada'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()->load(['Empleado.Departamento','Roles']),
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ]);
    }

    public function getPermisos()
    {
        return Auth::user()->getPermissions();
    }

    public function show(Request $request)
    {
        $user = Usuario::with('Roles', 'Empleado.Departamento')
        ->with('DepartamentosAsignados', function ($q) {
            $q->orderBy('nombre','asc');
        });
        $user->when($request->id === "ENABLE", function ($q) {
            return $q->where("activo", "si");
        });

        $user->when($request->id === "DISABLED", function ($q) {
            return $q->where("activo", "no");
        });

        $user->when($request->id > 0, function ($q) use ($request) {
            return $q->find($request->id);
        });
        if ($request->id > 0) {
            return response($user->first());
        }

        return response($user->get());
    }

    public function store(Request $request)
    {
        try {
            $user = new Usuario();
            if(isset($request->id)) {
                $user = Usuario::find($request->id);
                $user->usuario = $request->usuario;
                $user->empleado_id = $request->empleado_id;
                $user->email = $request->email;
            } else {
                if ($request->password !== $request->confirmpassword) {
                    return response(['message' => 'La contraseña capturada son diferentes.']);
                }
                $user->usuario = $request->usuario;
                $user->empleado_id = $request->empleado_id;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
            }

            if ($user->save()) {
                $mdoel = Usuario::with('Empleado.Departamento')->find($user->id);
                return response()->json($mdoel);
            } else
                return response(['message' => 'Favor de revisar los datos']);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
      try {
        $user = Usuario::with('Empleado.Departamento', 'Roles.Permisos','DepartamentosAsignados')->find($request->id);

        $user->usuario = $request->usuario;
        $user->empleado_id = $request->empleado_id;
        $user->email = $request->email;
        if ($user->save()) {
            return response()->json($user);
        } else {
            return response(['message' => 'Favor de revisar los datos']);
        }
      } catch (Exception $e) {
        return response(['message' => $e->getMessage()]);
      }
    }

    public function rol(Request $request)
    {
        $user = Usuario::find($request->id);
        $user->Roles()->sync(array($request->rol_id));
        return response($user);
    }

    public function setDepartamento(Request $request)
    {
        $usuario = Usuario::with('DepartamentosAsignados')->find($request->id);
        $Permisos = $usuario->DepartamentosAsignados;
        $PermisosReq = $request->departamentos;

        foreach ($Permisos as $p) {
            $assign = false;
            foreach ($PermisosReq as $pr) {
                if ($p->id === $pr['id']) {
                    $assign = true;
                    break;
                }
            }
            if(!$assign) $usuario->DepartamentosAsignados()->detach($p->id);
        }
        foreach ($PermisosReq as $pr) {
            $assign = false;
            foreach ($Permisos as $p) {
                if ($pr['id'] === $p->id) {
                    $assign = true;
                    break;
                }
            }
            if(!$assign) $usuario->DepartamentosAsignados()->attach($pr['id']);
        }

        $model = Usuario::with('DepartamentosAsignados')->find($request->id);
        return response()->json($model);
    }

    public function changePassword(Request $request)
    {
        $user = Usuario::with('Roles.Permisos', 'Empleado.Departamento')->find($request->id);

        if (isset($user)) {
            if ($request->new_password === $request->other_new_password) {
                try {
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                    return response($user);
                } catch (Exception $e) {
                    return response(['message' => $e->getMessage()]);
                }
            } else
                return response(['message' => 'Las contraseñas no coinciden, vuelva a intentarlo.']);
        } else
            return response(['message' => 'Id de usuario no encontrado']);
    }
}

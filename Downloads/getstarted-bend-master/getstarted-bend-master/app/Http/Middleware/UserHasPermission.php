<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Contracts\Auth\Guard;

class UserHasPermission
{
    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new UserHasPermission instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $closure
     * @param array|string             $permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if ($this->auth->check()) {
            if (!$this->auth->user()->can($permissions)) {
                if ($request->ajax()) {
                    return response('Unautho1rized.', 403);
                }
                return response('Unauthorized.', 401);
                //abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}

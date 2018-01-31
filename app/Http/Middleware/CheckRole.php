<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $user = JWTAuth::toUser($request->header('authorization'));
        $actions = $request->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        if ($user->hasRole($roles) || !$roles) {
            return $next($request);
        } else {
            $response = [
                'message' => 'Unauthorized Request'
            ];
            return self::sendErrorResponse($response);
        }
    }

    protected function sendErrorResponse($data)
    {
        $response = [
            'status' => 'error',
            'data' => $data
        ];
        return response()->json($response, 401);
    }
}

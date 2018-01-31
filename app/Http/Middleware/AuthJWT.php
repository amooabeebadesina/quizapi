<?php

namespace App\Http\Middleware;

use App\Traits\JSONResponse;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    use JSONResponse;

    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::toUser($request->header('authorization'));
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->sendErrorResponse('Invalid Token');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->sendErrorResponse('Expired Token');
            } else {
                return $this->sendErrorResponse('Token Required');
            }
        }
        return $next($request);
    }
}

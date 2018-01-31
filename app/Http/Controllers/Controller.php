<?php

namespace App\Http\Controllers;

use App\Traits\JSONResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tymon\JWTAuth\Facades\JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JSONResponse;

    public function getUserFromToken(Request $request)
    {
        $user = JWTAuth::toUser($request->header('authorization'));
        return $user;
    }
}

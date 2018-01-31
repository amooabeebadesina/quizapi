<?php

namespace App\Traits;
/**
 * Created by PhpStorm.
 * User: ABEEB
 * Date: 1/31/2018
 * Time: 4:16 AM
 */
trait JSONResponse
{
    public function sendSuccessResponse($data)
    {
        $response = [
            'status' => 'success',
            'data' => $data
        ];
        return response()->json($response);
    }

    public function sendErrorResponse($data)
    {
        $response = [
            'status' => 'error',
            'data' => [
                'message' => $data
            ]
        ];
        return response()->json($response);
    }
}
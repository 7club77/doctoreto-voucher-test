<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

/**
 * @OA\Info(
 *    title="Doctoreto Wallet Voucher API",
 *    version="1.0.0",
 * )
 */

/**
 * @OA\SecurityScheme(
 *   securityScheme="Authorization",
 *   type="apiKey",
 *   description="Fill with the User ID",
 *   in="header",
 *   name="Authorization"
 * )
 */

    protected function success($data, $httpCode = 200)
    {
        $response = [
            'data' => $data,
        ];

        return response()->json($response, $httpCode);
    }

    protected function error($code, $httpCode, $message)
    {
        return response()->json(['code' => $code, 'message' => $message])->setStatusCode($httpCode);
    }
}

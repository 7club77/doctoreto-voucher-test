<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/v1/users/me/wallets",
     * summary="Get User Wallet",
     * description="Get the Auth User Wallet Balance",
     * operationId="getMyWallet",
     * security={{"Authorization":{}}},
     * tags={"User"},
     *     @OA\Header(
     *         header="Authorization",
     *         description="Authorization header",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful operation",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *       @OA\Property(property="code", type="int", example=0),
     *       @OA\Property(property="message", type="object",
     *           @OA\Property(
     *             property="balance",
     *             type="number",
     *             default=0,
     *             example=2000
     *           ))),

     *        ),
     *     )
     * )
     */

    public function getMyWallet(Request $request)
    {
        try {
            $data = $request->all();
            throw_if(!$request->hasHeader('Authorization'), new \RuntimeException('User ID is required'));
            $user = User::syncWithId($request->header('Authorization'));

            return $this->success(
                User::getMyWallet($user)
            );
        } catch (\Throwable $e) {
            return $this->error('400', 400, $e->getMessage());
        }
    }
}

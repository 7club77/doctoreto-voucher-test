<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\User;
use App\Services\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function __construct(Wallet $walletService)
    {

        $this->walletService = $walletService;
    }

    /**
     * @OA\Post(
     * path="/api/v1/wallets/deposit",
     * summary="Wallet Deposit",
     * description="Deposit Wallet Balance",
     * operationId="walletDeposit",
     * security={{"Authorization":{}}},
     * tags={"Wallet"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Deposit info",
     *    @OA\JsonContent(
     *       required={"amount"},
     *       @OA\Property(property="amount", type="integer", example=2000),
     *       @OA\Property(property="code", type="string", example="EHngqXUWF", maxLength=9),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Transaction successfully created",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *       @OA\Property(property="code", type="int", example=0),
     *       @OA\Property(property="message", type="object",
     *           @OA\Property(
     *             property="transaction_id",
     *             type="string",
     *             example="fb7148bc-415e-4410-9e59-37ca5934f19f"
     *           ))),

     *        ),
     *     )
     * )
     */

    public function deposit(Request $request)
    {
        try {
            $data = $request->all();
            throw_if(!$request->hasHeader('Authorization'), new \RuntimeException('User ID is required'));
            $user = User::syncWithId($request->header('Authorization'));

            return $this->success($this->walletService->deposit($data, $user)
            );
        } catch (\Throwable $e) {
            return $this->error('400', 400, $e->getMessage());
        }
    }

}

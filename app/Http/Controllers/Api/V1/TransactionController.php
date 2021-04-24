<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function __construct(Transaction $transactionService)
    {

        $this->transactionService = $transactionService;
    }

    /**
     * @OA\Post(
     * path="/api/v1/transactions/callback",
     * summary="Transactiom Callback",
     * description="Transactiom Callback",
     * operationId="transactionCallback",
     * security={{"Authorization":{}}},
     * tags={"Transaction"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Transaction info",
     *    @OA\JsonContent(
     *       required={"transaction_id"},
     *       @OA\Property(property="transaction_id", type="string", example="a0b53d8a-e9d1-4980-9e40-7370df4aa295"),
     *       @OA\Property(property="is_confirmed", type="int", example=1),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Transaction successfully confirmed",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *       @OA\Property(property="code", type="int", example=0),
     *       @OA\Property(property="message", type="string", example="پرداخت شما با موفقیت تایید شد")),

     *        ),
     *     )
     * )
     */

    public function transactionCallback(Request $request)
    {
        try {
            $data = $request->all();

            return $this->success($this->transactionService->transactionCallback($data)
            );
        } catch (\Throwable $e) {
            return $this->error('400', 400, $e->getMessage());
        }
    }

}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    public function __construct(Voucher $voucherService)
    {

        $this->voucherService = $voucherService;
    }

    /**
     * @OA\Post(
     * path="/api/v1/vouchers",
     * summary="Voucher Store",
     * description="Make a single Voucher",
     * operationId="storeVoucher",
     * tags={"Voucher"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass voucher info",
     *    @OA\JsonContent(
     *       required={"amount"},
     *       @OA\Property(property="amount", type="integer", example=2000),
     *       @OA\Property(property="usage_limit", type="integer", example=1000),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Voucher successfully created",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *       @OA\Property(property="code", type="int", example=0),
     *       @OA\Property(property="message", type="string", example="EHngqXUWF")),

     *        ),
     *     )
     * )
     */

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            return $this->success($this->voucherService->store($data), 201);
        } catch (\Throwable $e) {
            return $this->error('400', 400, $e->getMessage());
        }
    }
}

<?php

namespace App\Services;

use App\Http\Helper\WalletValidation;
use App\Services\Voucher;
use Throwable;

class Wallet
{

    private $validator;

    public function __construct(WalletValidation $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Deposit on a Wallet
     * @param array $data
     * @param \App\Models\User $user
     * @return array
     */
    public function deposit(array $data, \App\Models\User $user): array
    {

        try {

            $this->validator->depositRequest($data);

            $code = $data['code'] ?? null;
            $amount = $data['amount'];

            $transaction = $user->wallet->transaction($user, $amount);

            if ($code) {
                $voucher = app(Voucher::class)->redeemCode($code, $user, $transaction);
            }

            return ['code' => 0, 'message' => ['transaction_id' => $transaction->uuid]];
        } catch (Throwable $e) {
            return ['code' => 100, 'message' => $e->getMessage()];
        }

    }

}

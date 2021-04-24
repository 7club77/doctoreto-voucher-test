<?php

namespace App\Services;

use App\Http\Helper\TransactionValidation;
use App\Models\Transaction as TransactionModel;
use App\Models\Wallet as WalletModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction
{

    private $validator;

    public function __construct(TransactionValidation $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Create A Transaction
     * @param WalletModel $wallet
     * @param Model $model
     * @param int $amount
     * @param int $is_confirmed
     * @return TransactionModel $transaction
     */

    public function create(WalletModel $wallet, Model $model, int $amount, int $is_confirmed = 0): TransactionModel
    {
        return TransactionModel::create([
            'uuid' => (string) Str::uuid(),
            'payable_id' => $model->getKey(),
            'payable_type' => $model->getMorphClass(),
            'wallet_id' => $wallet->id,
            'type' => TransactionModel::DEPOSIT,
            'amount' => $amount,
            'is_confirmed' => $is_confirmed,
        ]);

    }

    /**
     * Transaction Callbak from Gateway
     * @param array $data
     * @return array
     */
    public function transactionCallback(array $data): array
    {

        try {

            $this->validator->transactionCallbackRequest($data);

            $is_confirmed = $data['is_confirmed'] ?? 0;
            $transaction_id = $data['transaction_id'];

            $transaction = TransactionModel::where('uuid', $transaction_id)->where('is_confirmed', 0)->first();

            if ($transaction && $is_confirmed == 1) {

                $transaction->update(['is_confirmed' => $is_confirmed]);

                $user = $transaction->wallet->user;
                $wallet = $user->deposit($transaction->amount);

                $user_voucher = $transaction->user_voucher;

                if ($user_voucher) {

                    $user_voucher->update(['is_confirmed' => $is_confirmed], ['updated_at' => false]);

                    $voucher = $user_voucher->voucher;

                    $voucher->increment('total_usage');

                    $transaction = $user->wallet->transaction($voucher, $voucher->value, $is_confirmed);

                    $user->deposit($voucher->value);

                }

            } else {

                throw new \Exception('تایید تراکنش انجام نشد');

            }

            return ['code' => 0, 'message' => ['message' => 'پرداخت شما با موفقیت تایید شد']];

        } catch (Throwable $e) {
            return ['code' => 100, 'message' => $e->getMessage()];
        }

    }
}

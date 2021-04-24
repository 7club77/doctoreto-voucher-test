<?php

namespace App\Services;

use App\Models\Voucher as VoucherModel;
use Illuminate\Database\Eloquent\Model;

class Voucher
{

    /**
     * Store voucher from request
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {

        $amount = $data['amount'] ?? 0;

        $usage_limit = $data['usage_limit'] ?? 0;

        try {

            $voucher = $this->create($amount, $usage_limit);

            return ['code' => 0, 'message' => $voucher->code];
        } catch (Throwable $e) {
            return ['code' => 100, 'message' => $e->getMessage()];
        }

    }

    /**
     * @param int $value
     * @param int $usage_limit
     * @return VoucherModel $voucher
     */
    public function create(int $value, int $usage_limit): VoucherModel
    {
        $voucherCode = $this->getCode();

        return VoucherModel::create([
            'code' => $voucherCode,
            'value' => $value,
            'usage_limit' => $usage_limit,
        ]);

    }

    /**
     * @param string $code
     * @return VoucherModel $voucher
     */
    public function check(string $code): VoucherModel
    {

        $voucher = VoucherModel::whereCode($code)->first();

        throw_if(is_null($voucher),
            new \RuntimeException('کد تخفیف اشتباه است')
        );

        throw_if(!$voucher->hasCapacity(),
            new \RuntimeException('کد تخفیف قابل استفاده نیست')
        );

        return $voucher;
    }

    /**
     * @return string $code
     */
    protected function getCode(): string
    {
        $code = $this->genCode();

        while (VoucherModel::whereCode($code)->count() > 0) {
            $code = $this->genCode();
        }

        return $code;
    }

    /**
     * @param int $length
     * @return string $randomString
     */
    public function genCode($length = 9): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @param string $code
     * @param App\Models\User $user
     * @param ? App\Models\Transaction $transaction
     * @param int $is_confirmed
     * @return VoucherModel $voucher
     */
    public function redeemCode(string $code, \App\Models\User $user,  ? \App\Models\Transaction $transaction = null, int $is_confirmed = 0) : VoucherModel
    {
        $voucher = $this->check($code);

        throw_if($voucher->users()->wherePivot('user_id', $user->id)->where('is_confirmed', 1)->exists(),
            new \RuntimeException('امکان استفاده مجدد کد تخفیف وجود ندارد')
        );

        $user->vouchers()->attach($voucher, [
            'transaction_id' => $transaction ? $transaction->id : null,
            'redeemed_at' => now(),
            'is_confirmed' => $is_confirmed,
        ]);

        if ($is_confirmed === 1) {
            $voucher->increment('total_usage');
        }

        return $voucher;
    }

    /**
     * @param VoucherModel $voucher
     * @param App\Models\User $user
     * @param ? App\Models\Transaction $transaction
     * @param int $is_confirmed
     * @return mixed
     */
    public function redeemVoucher(VoucherModel $voucher, \App\Models\User $user,  ? \App\Models\Transaction $transaction = null, int $is_confirmed = 0)
    {
        return $this->redeemCode($voucher->code, $user, $transaction, $is_confirmed);
    }
}

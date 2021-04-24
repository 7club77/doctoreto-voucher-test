<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Voucher as VoucherModel;
use App\Services\Voucher;
use Illuminate\Database\Seeder;

class UserVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $voucher = VoucherModel::orderBy('id', 'desc')->first();

        $users = User::limit(1000)->get();

        $users->each(function ($user) use ($voucher) {
            app(Voucher::class)->redeemVoucher($voucher, $user, null, 1);
            $user->deposit($voucher->value);
        });

    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voucher as VoucherModel;
use App\Services\Voucher;
use Tests\TestCase;

class VoucherTest extends TestCase
{

    public function testSuccessVoucherCode()
    {

        $user = User::factory()->create();

        $user->deposit();

        $voucher = app(Voucher::class)->create(2000, 1000);

        $attributes = ['code' => $voucher->code, 'amount' => 2000];

        $response = $this->withHeaders(['Authorization' => $user->id])
            ->post('api/v1/wallets/deposit', $attributes);

        $response->assertJson(['data' => ['code' => 0]]);
    }

    public function testInvalidVoucherCode()
    {

        $user = User::first();

        $attributes = ['code' => '123456789', 'amount' => 2000];

        $response = $this->withHeaders(['Authorization' => $user->id])
            ->post('api/v1/wallets/deposit', $attributes);

        $response->assertJson(['data' => ['message' => 'کد تخفیف اشتباه است']]);
    }

    public function testVoucherMoreThanLimit()
    {

        $user = User::factory()->create();

        $user->deposit();

        $voucher = VoucherModel::find(1);

        $attributes = ['code' => $voucher->code, 'amount' => 2000];

        $response = $this->withHeaders(['Authorization' => $user->id])
            ->post('api/v1/wallets/deposit', $attributes);

        $response->assertJson(['data' => ['message' => 'کد تخفیف قابل استفاده نیست']]);
    }

    public function testVoucherAlreadyRedeemed()
    {

        $user = User::factory()->create();

        $user->deposit();

        $voucher = app(Voucher::class)->create(2000, 1000);

        $attributes = ['code' => $voucher->code, 'amount' => 2000];

        $response = $this->withHeaders(['Authorization' => $user->id])
            ->post('api/v1/wallets/deposit', $attributes);

        $attributes = ['transaction_id' => $response->getOriginalContent()['data']['message']['transaction_id'], 'is_confirmed' => 1];

        $response = $this->withHeaders(['Authorization' => $user->id])
            ->post('api/v1/transactions/callback', $attributes);

        $attributes = ['code' => $voucher->code, 'amount' => 2000];

        $response = $this->withHeaders(['Authorization' => $user->id])
            ->post('api/v1/wallets/deposit', $attributes);

        $response->assertJson(['data' => ['message' => 'امکان استفاده مجدد کد تخفیف وجود ندارد']]);
    }
}

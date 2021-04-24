<?php

namespace Database\Seeders;

use App\Services\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        app(Voucher::class)->create(2000, 1000);

    }
}

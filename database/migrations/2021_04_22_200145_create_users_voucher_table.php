<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersVoucherTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        Schema::create('users_voucher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('voucher_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamp('redeemed_at');
            $table->boolean('is_confirmed')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->foreign('transaction_id')->references('id')->on('transactions');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users_voucher');
    }
}

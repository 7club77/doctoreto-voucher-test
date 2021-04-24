<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {

    Route::get('users/me/wallets', 'UserController@getMyWallet');

    Route::resource('vouchers', 'VoucherController', ['only' => ['store']]);

    Route::post('wallets/deposit', 'WalletController@deposit');

    Route::post('transactions/callback', 'TransactionController@transactionCallback');

});

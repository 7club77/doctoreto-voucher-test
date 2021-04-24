<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const DEPOSIT = 'deposit';

    protected $fillable = [
        'uuid',
        'payable_type',
        'payable_id',
        'wallet_id',
        'type',
        'amount',
        'is_confirmed',
    ];

    public function wallet()
    {
        return $this->belongsTo('\App\Models\Wallet');
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function user_voucher()
    {
        return $this->hasOne('App\Models\UserVoucher');
    }

    public function isConfirmed()
    {
        return $this->is_confirmed === 1 ? true : false;
    }
}

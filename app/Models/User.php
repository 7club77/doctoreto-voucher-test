<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function vouchers()
    {
        return $this->belongsToMany('App\Models\Voucher', 'users_voucher')->withPivot('redeemed_at');
    }

    public function wallet()
    {
        return $this->hasOne('\App\Models\Wallet');
    }

    public function transactions()
    {
        return $this->morphMany('\App\Models\Transaction', 'payable');
    }

    public function deposit(int $amount = 0)
    {

        if ($this->wallet) {

            $this->wallet()->increment('balance', $amount);

        } else {

            $this->wallet = $this->wallet()->create(['balance' => $amount]);
        }

        return $this->wallet;

    }

    public function getBalance()
    {
        return $this->wallet->balance;

    }

}

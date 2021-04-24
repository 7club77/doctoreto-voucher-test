<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    protected $fillable = [
        'code',
        'value',
        'usage_limit',
        'total_usage',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'vouchers';

    }

    /**
     * Get the users who redeemed this voucher.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'users_voucher')->withPivot('redeemed_at');
    }

    public function transactions()
    {
        return $this->morphMany('\App\Models\Transaction', 'payable');
    }

    public function hasCapacity(): bool
    {
        if ($this->usage_limit === 0) {
            return true;
        }

        if ($this->total_usage < $this->usage_limit) {
            return true;
        } else {
            return false;
        }

    }
}

<?php

namespace App\Models;

use App\Services\Transaction;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'decimal_places' => 'int',
    ];

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }

    public function transactions()
    {
        return $this->hasMany('\App\Models\Transaction');
    }

    public function transaction($model, int $amount, int $is_confirmed = 0)
    {
        return app(Transaction::class)->create($this, $model, $amount, $is_confirmed);

    }
}

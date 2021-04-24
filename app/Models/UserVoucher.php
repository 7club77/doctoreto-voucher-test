<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;

    protected $table = 'users_voucher';

    protected $fillable = ['is_confirmed'];

    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
    }
}

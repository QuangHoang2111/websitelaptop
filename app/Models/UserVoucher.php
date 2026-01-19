<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    protected $table = 'user_vouchers';

    protected $fillable = [
        'userid',
        'voucherid',
        'used_at',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Address extends Model
{

    protected $fillable = [
        'userid',
        'name',
        'phone',
        'address',
        'city',
        'ward',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}

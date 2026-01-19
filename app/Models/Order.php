<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'total',
        'name',
        'phone',
        'address',
        'city',
        'ward',
        'status',
        'payment_method',
        'payment_status',
        'delivered_date',
        'cancelled_date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    
}

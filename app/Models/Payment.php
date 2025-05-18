<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_id',
        'payment_method',
        'total_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

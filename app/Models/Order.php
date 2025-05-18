<?php

namespace App\Models;

use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'user_address_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getProfit()
    {
        $profit = 0;

        foreach ($this->orderItems as $orderItem) {
            $product = $orderItem->product;

            if ($product) {
                $profit += ($product->price - $product->cost_price) * $orderItem->quantity;
            }
        }

        return $profit;
    }
    public function userAddress()
{
    return $this->belongsTo(UserAddress::class);
}

}
